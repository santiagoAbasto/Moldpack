<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Categoria extends Model
{
    protected $fillable = [
        'id','nombre', 'imagen','orden'
    ];

     protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }

    public function obtenerAplicacion(){
        return $this->hasMany('App\Models\AplicacionRelacion','producto_id','id');
    }

    public function obtenerProductos() {
        return $this->hasMany ('App\Models\FamiliaProducto','categorias_id','id');
    }
    public function obtenerListaProductos() {
        return $this->hasMany ('App\Models\Producto','categorias_id','id');
    }

}
