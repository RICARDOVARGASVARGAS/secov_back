<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'first_name',
        'last_name',
        'birth_date',
        'image',
        'email',
        'phone_number',
        'address',
        'gender',
    ];

    protected $allowFilter = [
        'document_type',
        'document_number',
        'name',
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'phone_number',
        'address',
        'gender',
    ];

    protected $allowIncluded = ['cars', 'cars.brand', 'cars.typeCar', 'cars.group', 'cars.year', 'cars.color', 'cars.example', 'licenses'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
