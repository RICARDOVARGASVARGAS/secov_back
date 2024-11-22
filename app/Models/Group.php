<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    protected $allowFilter = ['name', 'description'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
