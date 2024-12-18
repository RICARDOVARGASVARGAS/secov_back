<?php

namespace App\Models;

use App\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory, QueryTrait;

    protected $fillable = [
        'number',
        'class',
        'category',
        'issue_date',
        'renewal_date',
        'file',
        'driver_id',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
