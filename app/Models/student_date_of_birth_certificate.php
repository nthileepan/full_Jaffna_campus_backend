<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_date_of_birth_certificate extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'date_of_birth_certificate',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
