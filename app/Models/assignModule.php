<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignModule extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'assigntomodule_id',
        'lecture_name',
        'department_name',
        'module_name',
    ];
}
