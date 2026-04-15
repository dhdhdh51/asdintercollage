<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Fee, FeeCategory, FeeStructure, SchoolClass, Student, Transaction};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with(['student.user', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $fees       = $query->paginate(20);
        $categories = FeeCategory::where('is_active', true)->get();
        $stats = [
            'total_collected' => Fee::where('status', 'paid')->sum('paid_amount'),
            'total_pending'   => Fee::whereIn('status', ['pending', 'partial'])->sum('balance'),
            'total_overdue'   => Fee::where('status', 'overdue')->count(),
        ];

        return view('admin.fees.index', compact('fees', 'categories', 'stats'));
    }

    public function create()
    {
        $students   = Student::with('user')->where('is_active', true)->get();
        $categories = FeeCategory::where('is_active', true)->get();
        return view('admin.fees.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'      => 'required|exists:students,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount'          => 'required|numeric|min:1',
            'due_date'        => 'required|date',
            'academic_year'   => 'required|digits:4',
        ]);

        $amount = $request->amount;
        $discount = $request->discount ?? 0;
        $fine = $request->fine ?? 0;
        $balance = $amount - $discount + $fine;

        Fee::create([
            'invoice_number'  => Fee::generateInvoice(),
            'student_id'      => $request->student_id,
            'fee_category_id' => $request->fee_category_id,
            'amount'          => $amount,
            'discount'        => $discount,
            'fine'            => $fine,
            'balance'         => $balance,
            'status'          => 'pending',
            'due_date'        => $request->due_date,
            'month'           => $request->month,
            'academic_year'   => $request->academic_year,
            'remarks'         => $request->remarks,
        ]);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee record created successfully.');
    }

    public function show(Fee $fee)
    {
        $fee->load(['student.user', 'student.class', 'category', 'transactions']);
        return view('admin.fees.show', compact('fee'));
    }

    public function collectCash(Request $request, Fee $fee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $fee->balance,
        ]);

        $paidAmount = $fee->paid_amount + $request->amount;
        $newBalance = $fee->amount - $fee->discount + $fee->fine - $paidAmount;
        $status = $newBalance <= 0 ? 'paid' : 'partial';

        $fee->update([
            'paid_amount' => $paidAmount,
            'balance'     => max(0, $newBalance),
            'status'      => $status,
            'paid_date'   => now(),
        ]);

        Transaction::create([
            'transaction_id'   => 'CASH' . time(),
            'invoice_number'   => $fee->invoice_number,
            'student_id'       => $fee->student_id,
            'fee_id'           => $fee->id,
            'amount'           => $request->amount,
            'payment_method'   => 'cash',
            'status'           => 'success',
            'receipt_number'   => 'RCP' . time(),
        ]);

        return redirect()->back()->with('success', 'Cash payment recorded successfully.');
    }

    /**
     * Generate PDF invoice for a fee record.
     */
    public function invoice(Fee $fee)
    {
        $fee->load(['student.user', 'student.class', 'category', 'transactions']);
        $pdf = Pdf::loadView('admin.fees.invoice', compact('fee'));
        return $pdf->download('invoice-' . $fee->invoice_number . '.pdf');
    }

    public function categories()
    {
        $categories = FeeCategory::withCount('fees')->paginate(20);
        return view('admin.fees.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:fee_categories,name']);
        FeeCategory::create(['name' => $request->name, 'description' => $request->description]);
        return redirect()->back()->with('success', 'Fee category created.');
    }

    public function transactions()
    {
        $transactions = Transaction::with(['student.user', 'fee.category'])
            ->latest()->paginate(20);
        $stats = [
            'total'   => Transaction::where('status', 'success')->sum('amount'),
            'today'   => Transaction::where('status', 'success')->whereDate('created_at', today())->sum('amount'),
            'pending' => Transaction::where('status', 'pending')->count(),
        ];
        return view('admin.fees.transactions', compact('transactions', 'stats'));
    }
}
