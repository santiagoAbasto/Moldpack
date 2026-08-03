<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\ProductoImport;
use App\Imports\ComprarImport;

class ComprarMultiSheeImport implements WithMultipleSheets 
{
  
    public function sheets(): array
    {
        return [
            0 => new ComprarImport(),
            //0 => new ProductoImport(),            
        ];
    }
}
