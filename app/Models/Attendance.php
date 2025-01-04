<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    protected $fillable = [
        'student_id',
        'lecture_id',
        'time_table_id',
        'attendance_at',
        'verification_type',
        'otp_used',
        'status',
    ];
}
