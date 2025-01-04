<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'prefix',
        'department_name',
        'department_short_name',
        'department_status',
    ];

}
