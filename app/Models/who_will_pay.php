<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class who_will_pay extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'name',
        'address',
        'address_official',
        'city',
        'Province',
        'postal_code',
        'country',
        'phone_number',
        'email',
        'scholarship',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
