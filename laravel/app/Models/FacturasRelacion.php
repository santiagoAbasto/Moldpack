<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacturasRelacion extends Model
{
    protected $fillable = [
        'id','producto_id','relacion_id', 'descuento','subtotal'
    ];

    use HasFactory;

    public function obtenerPedido(){
        return $this->belongsTo('App\Models\Pedido','pedido_id');
    }
	
	public function pedidomod(){
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
