<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Obtén todos los productos con sus presentaciones
        return Producto::with('presentaciones')->get();
    }

    public function map($producto): array
    {
        $rows = [];
        foreach ($producto->presentaciones as $presentacion) {
            $rows[] = [
                $producto->nombre,
                $presentacion->codigo,
                $presentacion->stock,
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Código',
            'Stock',
        ];
    }
}
