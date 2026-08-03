<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\SoporteImport;

class SoporteMultiSheeImport implements WithMultipleSheets 
{
  
    public function sheets(): array
    {
        return [
            0 => new SoporteImport(),
            //0 => new ProductoImport(),            
        ];
    }
}
