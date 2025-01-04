<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class moduleModel extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'module'; // Ensure this matches the table name in your database

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'module_id'; // Use the actual primary key column in your table

    // Indicate whether the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;

    protected $fillable = [
        'module_id',
        'module_name',
        'department_shortname',
        'module_hours',
        'module_status'
    ];
    public function department()
    {
        return $this->belongsTo(departmentModel::class, 'department_shortname', 'department_id');
    }
    
}
