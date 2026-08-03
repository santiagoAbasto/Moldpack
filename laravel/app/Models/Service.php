<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    protected $fillable = [
        'id','nombre'
    ];

     protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }
}
