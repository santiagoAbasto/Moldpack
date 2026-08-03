<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientesExport implements FromCollection, WithHeadings
{
    protected $clientes;

    public function __construct($clientes)
    {
        $this->clientes = $clientes;
    }
    public function collection()
    {
        return collect($this->clientes)->map(function ($cliente) {
            return [
                'Cliente' => $cliente['cliente']->nombre, // Ajusta según tu modelo
                'Total' => $cliente['total'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Total',
        ];
    }
}