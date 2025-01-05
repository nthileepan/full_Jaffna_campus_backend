<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = ['name', 'slug', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function privileges()
    {
        return $this->belongsToMany(Privilege::class);
    }

    // Helper method to check if role has specific privilege
    public function hasPrivilege($privilege)
    {
        return $this->privileges->contains('slug', $privilege);
    }
    
    use HasFactory;
}
