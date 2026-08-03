<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PresentacionRelacion;
use Illuminate\Support\Facades\DB;
use stdClass;

class Color extends Model
{
    protected $fillable = [
        'id','categoria_id','nombre', 'imagen','orden'
    ];

     protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }    
}
