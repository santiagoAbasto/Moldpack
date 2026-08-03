<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresentacionRelacion extends Model
{
    protected $fillable = [
        'id','producto_id','relacion_id'
    ];

    use HasFactory;

    public function obtenerProducto(){        
        return $this->belongsTo('App\Models\Producto','producto_id');
    }
	public function producto() { 
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
