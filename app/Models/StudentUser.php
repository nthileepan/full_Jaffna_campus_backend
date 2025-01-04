<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentUser extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'Student_users';

    // Define the fields that can be mass-assigned
    protected $fillable = [
        'student_id',
        'emailId',
        'password',
        'status',
    ];

    // Hide sensitive fields from JSON output
    protected $hidden = [
        'password',
    ];
}
