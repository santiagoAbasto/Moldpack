<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Service;
class ComprarImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $service = new Service();
        
        $service->nombre = $row['nombre'] ?? '';
        $service->provincia = $row['provincia'] ?? '';
        $service->localidad = $row['localidad'] ?? '';
        $service->correo = $row['correo'] ?? '';
        $service->telefono = $row['telefono'] ?? '';
        $service->latitud = $row['latitud'] ?? '';
        $service->longitud = $row['longitud'] ?? '';
        $service->horario = $row['horario'] ?? '';
        $service->direccion = $row['direccion'] ?? '';
        $service->seccion = 'comprar';
        
        $service->save();
        
        return $service;
    }

}
