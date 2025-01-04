<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lectureModel extends Model
{
    use HasFactory;

    protected $table = 'lecture';
    protected $primaryKey = 'lecture_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'lecture_id',
        'lecture_name',
        'lecture_phone_number',
        'lecture_gender',
        'off_day',
        'lecture_status',
    ];
}
