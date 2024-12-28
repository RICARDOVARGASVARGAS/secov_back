<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'issue_date',
        'expiration_date',
        'file_permit',
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
