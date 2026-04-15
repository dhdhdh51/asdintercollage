<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id', 'employee_id', 'qualification', 'specialization',
        'joining_date', 'salary', 'address', 'dob', 'gender',
        'emergency_contact', 'is_active'
    ];
    protected $casts = ['joining_date' => 'date', 'dob' => 'date', 'is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function classSubjects() { return $this->hasMany(TeacherClassSubject::class); }
    public function classes() { return $this->belongsToMany(SchoolClass::class, 'teacher_class_subjects', 'teacher_id', 'class_id'); }
    public function subjects() { return $this->belongsToMany(Subject::class, 'teacher_class_subjects'); }
    public function homeworks() { return $this->hasMany(Homework::class); }
    public function attendances() { return $this->hasMany(Attendance::class, 'marked_by'); }
}
