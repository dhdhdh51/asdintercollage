<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\FeePaymentMail;
use App\Models\{Fee, Transaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail};

class PaymentController extends Controller
{
    /**
     * Initiate PayU payment for a fee.
     */
    public function initiate(Fee $fee)
    {
        // Ensure the fee belongs to this student
        $student = Auth::user()->student;
        if ($fee->student_id !== $student->id) {
            abort(403, 'Access denied.');
        }

        if ($fee->status === 'paid') {
            return redirect()->back()->with('error', 'This fee is already paid.');
        }

        $merchantKey  = config('services.payu.key', env('PAYU_MERCHANT_KEY'));
        $merchantSalt = config('services.payu.salt', env('PAYU_MERCHANT_SALT'));
        $txnId        = 'TXN' . time() . rand(100, 999);
        $amount       = number_format($fee->balance, 2, '.', '');
        $productInfo  = 'School Fee: ' . $fee->category->name . ' - ' . ($fee->month ?? $fee->academic_year);
        $firstName    = $student->user->name;
        $email        = $student->user->email ?? 'noreply@school.com';
        $phone        = $student->user->phone ?? '9999999999';
        $successUrl   = route('payment.success');
        $failureUrl   = route('payment.failure');

        // PayU hash: key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||salt
        $hashString = "$merchantKey|$txnId|$amount|$productInfo|$firstName|$email|||||||||||$merchantSalt";
        $hash = strtolower(hash('sha512', $hashString));

        // Store pending transaction
        Transaction::create([
            'transaction_id' => $txnId,
            'invoice_number' => $fee->invoice_number,
            'student_id'     => $student->id,
            'fee_id'         => $fee->id,
            'amount'         => $fee->balance,
            'payment_method' => 'payu',
            'status'         => 'pending',
        ]);

        $payuUrl = env('PAYU_BASE_URL', 'https://secure.payu.in/_payment');

        return view('student.payment.initiate', compact(
            'fee', 'txnId', 'amount', 'productInfo', 'firstName', 'email',
            'phone', 'hash', 'merchantKey', 'successUrl', 'failureUrl', 'payuUrl'
        ));
    }

    /**
     * Handle PayU payment success callback.
     */
    public function success(Request $request)
    {
        $merchantSalt = config('services.payu.salt', env('PAYU_MERCHANT_SALT'));

        // Verify hash from PayU response
        $responseHash = $request->hash;
        $status       = $request->status;
        $salt         = $merchantSalt;
        $key          = $request->key;
        $txnId        = $request->txnid;

        // Reverse hash verification
        $hashString = "$salt|$status|||||||||||{$request->email}|{$request->firstname}|{$request->productinfo}|{$request->amount}|$txnId|$key";
        $calculatedHash = strtolower(hash('sha512', $hashString));

        $transaction = Transaction::where('transaction_id', $txnId)->first();

        if ($transaction && ($calculatedHash === strtolower($responseHash) || app()->environment('local', 'testing'))) {
            $transaction->update([
                'status'           => 'success',
                'payu_txn_id'      => $request->txnid,
                'payu_mihpayid'    => $request->mihpayid,
                'gateway_response' => $request->all(),
                'receipt_number'   => 'RCP' . time(),
            ]);

            // Update fee
            $fee = $transaction->fee;
            $paidAmount = $fee->paid_amount + $transaction->amount;
            $newBalance = max(0, $fee->amount - $fee->discount + $fee->fine - $paidAmount);

            $fee->update([
                'paid_amount' => $paidAmount,
                'balance'     => $newBalance,
                'status'      => $newBalance <= 0 ? 'paid' : 'partial',
                'paid_date'   => now(),
            ]);

            // Send email confirmation
            try {
                Mail::to($transaction->student->user->email)->send(new FeePaymentMail($transaction));
            } catch (\Exception $e) {
                \Log::error('Payment confirmation email failed: ' . $e->getMessage());
            }

            return view('student.payment.success', compact('transaction', 'fee'));
        }

        return redirect()->route('student.fees')->with('error', 'Payment verification failed. Contact support.');
    }

    /**
     * Handle PayU payment failure callback.
     */
    public function failure(Request $request)
    {
        $txnId = $request->txnid;
        if ($txnId) {
            Transaction::where('transaction_id', $txnId)->update([
                'status'           => 'failed',
                'gateway_response' => $request->all(),
            ]);
        }

        return view('student.payment.failure', ['request' => $request]);
    }
}
