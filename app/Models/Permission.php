<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_es',
        'name_en',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
