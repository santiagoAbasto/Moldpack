<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetsExport implements FromView, WithMultipleSheets
{
    private $clientes;
    public function __construct($clientes)
    {
        $this->clientes = $clientes;
        
    }



    public function sheets(): array
    {
        return [
            'clientes' => new ClienteExport($this->clientes),
        ];
    }

    public function view(): View
    {
        
        //return view('exports.clientes', [
        //    'clientes' => $this->obj,
        //]);
    }
    
}
