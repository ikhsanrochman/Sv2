<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name'];

    // Many-to-many relation ke User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
