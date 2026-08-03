<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public function obtenerRelacionados(){
        return $this->hasMany('App\Models\FacturasRelacion','pedido_id','id');
    }
    
    public function obtenerCliente(){
        return $this->belongsTo('App\Models\Cliente','usuario_id');
    }
	
	public function cliente(){
        return $this->belongsTo(Cliente::class, 'usuario_id');
    }
	
	public function facturasRelacion(){ 
        return $this->hasMany(FacturasRelacion::class, 'pedido_id');
    }
}
