<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'uuid',
        'plate',
        'chassis',
        'motor',
        'image_car',
        'brand_id',
        'type_car_id',
        'group_id',
        'year_id',
        'color_id',
        'example_id',
        'driver_id',
        'group_number',
        'number_of_seats',
        'file_car',
    ];

    protected $allowFilter = [
        'plate',
        'chassis',
        'motor',
    ];

    protected $allowIncluded = [
        'brand',
        'typeCar',
        'group',
        'year',
        'color',
        'example',
        'driver',
        'inspections',
        'insurances',
        'permits',
        'latestInspection',
        'latestInsurance',
        'latestPermit',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function typeCar()
    {
        return $this->belongsTo(TypeCar::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function example()
    {
        return $this->belongsTo(Example::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    // obtener la última inspección
    public function latestInspection()
    {
        return $this->hasOne(Inspection::class)->latestOfMany();
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    // obtener la última póliza
    public function latestInsurance()
    {
        return $this->hasOne(Insurance::class)->latestOfMany();
    }

    public function permits()
    {
        return $this->hasMany(Permit::class);
    }

    // obtener el última permiso
    public function latestPermit()
    {
        return $this->hasOne(Permit::class)->latestOfMany();
    }
}
