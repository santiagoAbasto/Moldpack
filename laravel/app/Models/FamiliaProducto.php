<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FamiliaProducto extends Model
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

    public function obtenerRelacionados(){
        return $this->hasMany('App\Models\ProductoRelacion','producto_id','id');
    }

    public function obtenerProductos() {
        return $this->hasMany('App\Models\Producto','subcategorias_id')->orderBy('orden','asc');
    }

    public function obtenerProductoCategoria() {
        return $this->belongsTo ('App\Models\Categoria','categorias_id');
    }


    public function lista(){
        $prod = $this->hasMany('App\Models\Producto','categorias_id')->orderBy('orden','asc')->get();
        
        if($prod){
            if(isset($prod[0]->diametro)){
                return 0;
            }
        }
        return $prod;
    }

}
