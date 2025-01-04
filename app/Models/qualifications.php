<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qualifications extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'olexam',
        'olpath',
        'alexam',
        'alpath',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
