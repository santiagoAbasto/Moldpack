<?php

namespace App\Exports;

use App\Models\FacturasRelacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductosVendidosExport implements FromView
{
    protected $productosVendidos;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($productosVendidos, $fechaInicio, $fechaFin)
    {
        $this->productosVendidos = $productosVendidos;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }
    public function view(): View 
    { 
        return view('exports.productosVendidos', [ 
            'productosVendidos' => $this->productosVendidos, 
            'fechaInicio' => $this->fechaInicio, 
            'fechaFin' => $this->fechaFin
        ]); 
    }
    
}
