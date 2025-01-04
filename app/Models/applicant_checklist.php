<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applicant_checklist extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'newspaper',
        'seminar',
        'social_media',
        'open_events',
        'bcas_website',
        'leaflets',
        'student_review',
        'radio',
        'other',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
