<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name', 'numeric_value', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function sections() { return $this->hasMany(Section::class, 'class_id'); }
    public function subjects() { return $this->hasMany(Subject::class, 'class_id'); }
    public function students() { return $this->hasMany(Student::class, 'class_id'); }
    public function feeStructures() { return $this->hasMany(FeeStructure::class, 'class_id'); }
}
