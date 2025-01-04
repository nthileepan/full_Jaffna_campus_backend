<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    // Set the table name if it's different from the default (plural of model name)
    protected $table = 'time_tables';

    // Define the fillable attributes
    protected $fillable = [
        'subject',
        'lecture',
        'department',
        'batch',
        'lecture_hall',
        'start_date',
        'end_date',
    ];
    public function lecture()
    {
        return $this->belongsTo(lectureModel::class, 'lecture', 'lecture_id');
    }
}
