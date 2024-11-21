<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'chassis',
        'motor',
        'file_car',
        'brand_id',
        'type_car_id',
        'group_id',
        'year_id',
        'color_id',
        'example_id',
        'number_soat',
        'file_soat',
        'date_soat_issue',
        'date_soat_expiration',
        'file_technical_review',
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
    
}
