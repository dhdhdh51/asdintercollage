<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'invoice_number', 'student_id', 'fee_id',
        'amount', 'payment_method', 'status', 'payu_txn_id',
        'payu_mihpayid', 'gateway_response', 'receipt_number'
    ];
    protected $casts = ['gateway_response' => 'array'];

    public function student() { return $this->belongsTo(Student::class); }
    public function fee() { return $this->belongsTo(Fee::class); }
}
