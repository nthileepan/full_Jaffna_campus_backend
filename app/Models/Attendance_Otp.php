<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'lecture_id',
        'otp_value',
        'generated_at',
        'expires_at',
    ];
}
