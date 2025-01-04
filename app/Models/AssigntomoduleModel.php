<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssigntomoduleModel extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'aasigntomodule'; // Ensure this matches the table name in your database

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'assigntomodule_id'; // Use the actual primary key column in your table

    // Indicate whether the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the data type of the primary key
    protected $keyType = 'int';

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;    protected $fillable = [
        'assigntomodule_id',
        'lecture_name', // Changed from department_name to department_id
        'department_name',
        'module_name',
    ];
    
    // Define the relationship with Department model
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