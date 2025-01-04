<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches';

    protected $fillable = [
        'id',
        'prefix',
        'department_name', // Changed from department_name to department_id
        'batch_name',
        'batch_short_date',
        'batch_end_date',
        'batch_status',
    ];

    // Define the relationship with Department model
    public function department()
    {
        return $this->belongsTo(departmentModel::class, 'department_name', 'department_id');
    }
}