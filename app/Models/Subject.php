<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'class_id', 'max_marks', 'pass_marks', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function teachers() { return $this->belongsToMany(Teacher::class, 'teacher_class_subjects'); }
}
