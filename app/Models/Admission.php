<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'application_id', 'student_name', 'father_name', 'mother_name',
        'dob', 'gender', 'class_id', 'address', 'city', 'state', 'pincode',
        'phone', 'email', 'previous_school', 'previous_class', 'document_path',
        'status', 'remarks', 'reviewed_by', 'reviewed_at', 'academic_year'
    ];
    protected $casts = ['dob' => 'date', 'reviewed_at' => 'datetime'];

    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }

    public static function generateApplicationId(): string {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'APP' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }
}
