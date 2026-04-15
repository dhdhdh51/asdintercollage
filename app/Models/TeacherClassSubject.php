<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeacherClassSubject extends Model
{
    protected $table = 'teacher_class_subjects';
    protected $fillable = ['teacher_id', 'class_id', 'section_id', 'subject_id'];

    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
}
