<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id', 'class_id', 'section_id', 'subject_id',
        'date', 'status', 'remarks', 'marked_by'
    ];
    protected $casts = ['date' => 'date'];

    public function student() { return $this->belongsTo(Student::class); }
    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function markedBy() { return $this->belongsTo(User::class, 'marked_by'); }
}
