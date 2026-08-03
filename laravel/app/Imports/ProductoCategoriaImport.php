<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Producto;
use App\Models\FamiliaProducto;
use App\Models\Categoria;
use App\Models\PresentacionRelacion;

class ProductoCategoriaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { 
      return null;
        // if($row['fk_categoria_id'] == 0){
        //     $cat = Categoria::find($row['categoria_id']);
        //     if(!$cat){
        //         $cat = new Categoria();
        //     }

        // }else{
        //     $cat = FamiliaProducto::find($row['categoria_id']);
        //     if(!$cat){
        //         $cat = new FamiliaProducto();
        //         $cat->categorias_id = $row['fk_categoria_id'];
        //     }
        // }
        // $cat->id = $row['categoria_id'];
        // $cat->nombre = $row['categoria_nombre'];
        // $cat->destacar = $row['categoria_destacar'];
        // $cat->activa = $row['categoria_activa'];
        // $cat->orden = $row['categoria_orden'];
        // $cat->save();



        // if($row['fk_producto_id'] == 0){
        //     $prod = Producto::find($row['producto_id']);
        //     if(!$prod){            
        //         $prod = new Producto();
        //     }
        //     $prod->id = $row['producto_id'];
        //     $prod->nombre = $row['producto_nombre'];
        //     $prod->descripcion = $row['producto_descripcion'];
        //     $prod->save();

        //     $presentacion = PresentacionRelacion::where('producto_id','=',$prod->id)
        //     ->where('codigo','=', $row['producto_codigop'].$row['producto_unidades'])
        //     ->first();

        //     if(!$presentacion){
        //         $presentacion = new PresentacionRelacion();
        //     }

        //     $presentacion->producto_id = $prod->id;
        //     $presentacion->codigo = $row['producto_codigop'];
        //     $presentacion->precio = floatval($row['producto_precio']);
        //     $presentacion->stock = 0;
        //     $presentacion->activa = $row['producto_activo'];
        //     $presentacion->destacado = $row['producto_destacado'];
        //     $presentacion->presentacion = $row['producto_codigo']." U/".$row['producto_unidades'];
        //     $presentacion->save();
        // }else{
            // $presentacion = PresentacionRelacion::where('producto_id','=',$row['fk_producto_id'])
            // ->where('presentacion','=', $row['producto_codigo'].$row['producto_unidades'])
            // ->first();

            // if(!$presentacion){
            //     $presentacion = new PresentacionRelacion();
            // }
            
            // $presentacion->id = $row['producto_id'];
            // $presentacion->producto_id = $row['fk_producto_id'];
            // $presentacion->codigo = $row['producto_codigop'];
            // $presentacion->precio = floatval($row['producto_precio']);
            // $presentacion->stock = 0;
            // $presentacion->activa = $row['producto_activo'];
            // $presentacion->destacado = $row['producto_destacado'];
            // $presentacion->presentacion = $row['producto_codigo']." U/".$row['producto_unidades'];
            // $presentacion->save();

        //}
        
        // $cat = Categoria::find($row['fk_categoria_id']);
        // $flag = 0;
        // if(!$cat){
        //     $flag = 1;
        //     $cat = FamiliaProducto::find($row['fk_categoria_id']);
        // }
        // if(!$cat){
        //     $cat = new FamiliaProducto();
        //     return $cat;
        // }
        // $prod = Producto::find($row['fk_producto_id']);
        // if(!$prod){
        //     $prod = PresentacionRelacion::find($row['fk_producto_id']);
        //     return $prod;
        // }
        // if($flag == 0){
        //     $prod->categorias_id = $row['fk_categoria_id'];
        // }else{            
        //     $prod->subcategorias_id = $row['fk_categoria_id'];
        // }
        // $prod->save();
        
        // $prod = Producto::find($row['fk_producto_id']);
        // if($prod){
        //     $prod->imagen='public/productos/'.$row['productoimagen_imagen'];
        //     $prod->save();
        // }

    }

}
