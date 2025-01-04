<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emergency_contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'relationship',
        'address',
        'address_line',
        'city',
        'province',
        'postal_code',
        'email',
        'phone_number',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
