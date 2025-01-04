<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecturehallModel extends Model
{
    use HasFactory;

    protected $table = 'lecturehall';

    protected $primaryKey = 'lecturehall_id';

    public $incrementing = true;


    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'lecturehall_id',
        'lecturehall_name',
        'lecturehall_shortname',
        'lecturehall_status'
    ];
}
