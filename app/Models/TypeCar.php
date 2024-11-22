<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCar extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'name',
    ];

    protected $allowFilter = ['name'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
