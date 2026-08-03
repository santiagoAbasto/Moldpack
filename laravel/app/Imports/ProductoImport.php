<?php

namespace App\Imports;

use App\Models\Producto;
use App\Models\Producto_categoria;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Mockery\Undefined;

class ProductoImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        // $productoCaractegoria = new Producto([
        //    'codigo' => $row[0],
        //    'nombre' => $row[1]
        // ]);

        // $productoCaractegoria->save();
        
        
        $producto = Producto::where('codigo','=',$row['familia'])->first();

        if (!$producto) {
            return null;
        }

    if(isset($row['familia'])){
        if($row['familia'] == 24){
            
        }
    }

        
        
        $descripcion = explode('*',$row['descripcion']);
        $descripcion[0] = str_replace('  ','',$descripcion[0]);
                
        if( !isset($descripcion[1])){
            $descripcion[1] = "";
        }else{
            
        }
        
        if($producto->tabla !== null && $producto->tabla != ""){
            $json_tabla = json_decode($producto->tabla);
        }else{
            $json_tabla = [];
        }
            
            $obj= [];            
            $obj['col_0'] = $row['codigo'];
            $obj['col_1'] = $descripcion[0]." ".$producto->categoria_producto->nombre;
            $obj['col_2'] = $descripcion[1];
            $obj['col_3'] = "0";

            $flag=0;

            foreach($json_tabla as $item){

                if($item->col_0 == $row['codigo']){
                    $flag =1;
                }
            }
            if($flag == 0){
                array_push($json_tabla,$obj);
            }
            

            $producto->tabla = json_encode($json_tabla);                

        // return new Producto([
        //    'codigo' => $row['familia'],
        //    'nombre' => "",
        // ]);
        //dd($producto);
        // if($producto != null){
        //     $producto->precio = $row[3];
        // }else{
        //     $producto = producto::first();
        // }
         return $producto;
    }
}
