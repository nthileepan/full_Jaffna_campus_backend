<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departmentModel extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'department'; // Ensure this matches the table name in your database

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'department_id'; // Use the actual primary key column in your table

    // Indicate whether the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'department_id',
        'department_name',
        'department_shortname',
        'status'
    ];
}
