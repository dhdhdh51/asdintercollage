<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'invoice_number', 'student_id', 'fee_category_id', 'amount',
        'discount', 'fine', 'paid_amount', 'balance', 'status',
        'due_date', 'paid_date', 'month', 'academic_year', 'remarks'
    ];
    protected $casts = ['due_date' => 'date', 'paid_date' => 'date'];

    public function student() { return $this->belongsTo(Student::class); }
    public function category() { return $this->belongsTo(FeeCategory::class, 'fee_category_id'); }
    public function transactions() { return $this->hasMany(Transaction::class); }

    public static function generateInvoice(): string {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'INV' . $year . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
