<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class other_qualifications extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'qualifications',
        'qualifications_line',
        
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
