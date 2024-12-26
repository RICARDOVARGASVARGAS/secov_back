<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'number_insurance',
        'issue_date',
        'expiration_date',
        'file_insurance',
        'car_id',
    ];

    // validar si la fecha de vencimiento es mayor a la fecha actual
    public function getIsValidAttribute()
    {
        return $this->expiration_date >= now()->toDateString();
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
