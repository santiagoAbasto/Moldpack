<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PresentacionRelacion;
use Illuminate\Support\Facades\DB;
use stdClass;

class Producto extends Model
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
    
    public function obtenerCategoria(){
        $subCategoria = $this->obtenerSubCategoria;        
        if($subCategoria){
            return $subCategoria;
        }
        return $this->obtenerFamilia;
    }
    public function obtenerFamilia(){        
        return $this->belongsTo('App\Models\Categoria','categorias_id');
    }
    public function obtenerSubCategoria(){
        return $this->belongsTo('App\Models\FamiliaProducto','subcategorias_id');
    }
    public function obtenerRelacionados(){
        return $this->hasMany('App\Models\ProductoRelacion','producto_id','id');
    }

    public function obtenerColorRelacionados(){
        return $this->hasMany('App\Models\ColoresRelacion','producto_id','id');
    }
    public function obtenerColorToString(){
        $arrColores = $this->obtenerColorRelacionados;  
        $string = "";        
        if(isset($arrColores)){
            foreach($arrColores as $colores){
                $string .= str_replace(' ','-',$colores->colores->nombre)." ";
            }
        }
        return $string;
    }
    public function obtenerPresentacionRelacionados(){
        return $this->hasMany('App\Models\PresentacionRelacion','producto_id','id');
    }
    public function obtenerPresentacionToString(){
        $arrPresentacion = $this->obtenerPresentacionRelacionados;  
        $string = "";        
        if(isset($arrPresentacion)){
            foreach($arrPresentacion as $presentacion){
                $string .= str_replace(' ','-',$presentacion->presentacion)." ";
            }
        }
        return $string;
    }
    
    public function obtenerGaleria(){
        $galeria = $this->galeria;
        $galeria = explode(',',$galeria);
        return $galeria;
    }
	
	public function presentaciones() { 
        return $this->hasMany(PresentacionRelacion::class, 'producto_id');
    }
}
