<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\ProductoImport;
use App\Imports\ProductoCategoriaImport;

class ProductoMultiSheeImport implements WithMultipleSheets 
{
  
    public function sheets(): array
    {
        return [
            0 => new ProductoCategoriaImport(),
            //0 => new ProductoImport(),            
        ];
    }
}
