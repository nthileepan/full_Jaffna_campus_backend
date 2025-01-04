<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class name_of_course extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'preferred_mode',
        'program_applied_for',
        'course_name',
        'student_number',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
