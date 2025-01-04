<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_TimeTable extends Model
{
    use HasFactory;

      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'time_tables';

    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'prefix',
        'batch_id',
        'module_id',
        'lecturer_id',
        'department_id',
        'classroom',
        'slot_id',
        'status',
        'date',
        'start_date',
        'end_date',
    ];

    public function department()
    {
        return $this->belongsTo(Attendance_Department::class, 'department_id', 'id');
    }

    /**
     * Define the relationship with the Batch model.
     */
    public function batch()
    {
        return $this->belongsTo(Attendance_Batch::class, 'batch_id', 'id');
    }

    /**
     * Define the relationship with the Module model.
     */
    public function module()
    {
        return $this->belongsTo(Attendance_Module::class, 'module_id', 'id');
    }

    /**
     * Define the relationship with the Lecturer model.
     */
    public function lecturer()
    {
        return $this->belongsTo(Attendance_Lecture::class, 'lecturer_id', 'id');
    }

    /**
     * Define the relationship with the Slot model.
     */
    public function slot()
    {
        return $this->belongsTo(Attendance_Slot::class, 'slot_id', 'id');
    }


}
