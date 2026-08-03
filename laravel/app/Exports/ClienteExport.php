<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClienteExport implements FromView,WithTitle
{
    public function __construct($obj)
    {
        $this->obj = $obj;
    }
    public function title(): string
    {
        return 'clientess';
    }

    public function view(): View
    {
        
        return view('exports.clientes', [
            'clientes' => $this->obj,
        ]);
    }
    
}
