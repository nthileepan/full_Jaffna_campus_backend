<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personal_statement extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_reason',
        'self',
        'parents',
        'spouse',
        'other',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
