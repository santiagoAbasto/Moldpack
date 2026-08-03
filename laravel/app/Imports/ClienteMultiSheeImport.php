<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\ClienteCategoriaImport;

class ClienteMultiSheeImport implements WithMultipleSheets 
{
  
    public function sheets(): array
    {
        return [
            0 => new ClienteCategoriaImport(),
        ];
    }
}
