<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PedidoExport implements FromView
{
    private $pedidos;
    public function __construct($pedidos)
    {

        $this->pedidos = $pedidos;
        
    }
    public function view(): View
    {
        
        return view('exports.pedidos', [
            'pedidos' => $this->pedidos,
        ]);
    }
    
}
