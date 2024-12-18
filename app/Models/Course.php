<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'title',
        'date',
    ];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class);
    }
}
