<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'roll_number', 'class_id', 'section_id',
        'father_name', 'mother_name', 'father_phone', 'mother_phone', 'father_occupation',
        'dob', 'gender', 'address', 'city', 'state', 'pincode',
        'blood_group', 'religion', 'caste', 'admission_year', 'is_active'
    ];
    protected $casts = ['dob' => 'date', 'is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class); }
    public function fees() { return $this->hasMany(Fee::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function parents() { return $this->belongsToMany(ParentModel::class, 'parent_student', 'student_id', 'parent_id')->withPivot('relation'); }

    public static function generateId(): string {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'STU' . $year . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
