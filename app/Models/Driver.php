<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'uuid',
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
        'file_driver'
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

    protected $allowIncluded = ['cars', 'cars.brand', 'cars.typeCar', 'cars.group', 'cars.year', 'cars.color', 'cars.example', 'licenses', 'latestLicense'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    // obtener la última licencia
    public function latestLicense()
    {
        return $this->hasOne(License::class)->latestOfMany();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
