<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin_use extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'student_number',
        'total_fees',
        'registration_fees',
        'installment',
        'discount',
        'join_date',
        'end_date',
        'status',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
