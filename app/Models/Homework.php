<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $fillable = [
        'title', 'description', 'class_id', 'section_id', 'subject_id',
        'teacher_id', 'due_date', 'file_path', 'is_active'
    ];
    protected $casts = ['due_date' => 'date', 'is_active' => 'boolean'];

    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
}
