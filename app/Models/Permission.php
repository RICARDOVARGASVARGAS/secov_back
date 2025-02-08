<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'name_es',
        'name_en',
    ];

    protected $allowIncluded = ['roles'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
