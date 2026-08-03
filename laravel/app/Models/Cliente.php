<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use stdClass;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'password_encrypted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	protected $appends = ['isCliente'];

	
    public function descuentos(){
        return $this->hasMany('App\Models\DescuentoRelacion','cliente_id','id');
    }

    public function traerDescuento($id){
        $descuentos = $this->descuentos()->get();
        $return = null;

        foreach($descuentos as $item){
            if($item->categoria == $id )
            {
                $obj = new stdClass();                
                $obj->descuento = $item->descuento;                
                $return = $obj;
            }
        }

        return $return;
    }

    public function obtenerRelacionados(){
        return $this->hasMany('App\Models\DescuentoRelacion','cliente_id','id');
    }
    
    public function obtenerPedidos(){
        return $this->hasMany('App\Models\Pedido','usuario_id','id');
    }

    public function obtenerCategoriasRelacionados(){
        return $this->hasMany('App\Models\CategoriasRelacion','cliente_id','id');
    }

    public function obtenerProductoRelacionado($producto_id){
        $relaciones = $this->obtenerRelacionados()->get();
        $return = null;
        foreach($relaciones as $item){
            if($item->producto_id == $producto_id){
                $return = $item;
            }
        }
        return $return;
    }

    public function obtenerDescuento($producto_id){
        $relaciones = $this->obtenerProductoRelacionado($producto_id);
     
        $return = 1;

        if($relaciones){
            $descuento = 100 - $relaciones->descuento;
            $descuento = $descuento / 100;
            $return = $descuento;
         }//else if($this->descuento){
        //     $descuento = 100 - $this->descuento;
        //     $descuento = $descuento / 100;
        //     $return = $descuento;
        // }

        return $return;

    }

    public function descuentoGlobal(){
        $desc = $this->descuento;
        if($desc == 0){
            return $desc;
        }
        $descuento = 100 - $desc;
        $descuento = $descuento / 100;
        return $descuento;
    }
	


	public function getIsClienteAttribute()
	{
    	$pedido = Pedido::where('usuario_id', $this->id)->first();
   
    	return $pedido ? true : false;
	}
	public function pedidos()
    { 
        return $this->hasMany(Pedido::class,'usuario_id');
    }
	
}
