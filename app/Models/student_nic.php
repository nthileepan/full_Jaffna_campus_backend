<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_nic extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'nic',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
