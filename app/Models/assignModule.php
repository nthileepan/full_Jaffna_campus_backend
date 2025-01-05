<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignModule extends Model
{
    use HasFactory;

    protected $table = 'assign_modules';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'lecture_name',
        'department_name',
        'module_name',
    ];

    public function department()
    {
        return $this->belongsTo(departmentModel::class, 'department_name', 'department_id');
    }

    public function lecture()
    {
        return $this->belongsTo(lectureModel::class, 'lecture_name', 'lecture_id');
    }


    public function module()
    {
        return $this->belongsTo(moduleModel::class, 'module_name', 'module_id');
    }
}
