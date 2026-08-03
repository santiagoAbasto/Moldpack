<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FacturasRelacion;

class MiComando extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CalcularSubtotales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula los subtotales de los remitos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pedidos = FacturasRelacion::select('pedido_id')->where('factura', 'T')->pluck('pedido_id');
        foreach ($pedidos as $pedido){
            $pedidoId = $pedido;
            
            // Obtener el pedido y el cliente relacionado
            $factura = FacturasRelacion::where('pedido_id', $pedidoId)
            ->where('factura', 'T')
            ->with(['pedidomod.cliente'])
            ->first();
            try {
                $cliente = $factura->pedidomod->cliente;
                $descuento = $cliente->descuento;

                // Calcular el total del pedido con el descuento
                $items = json_decode($factura->pedido);
                $total = 0;
                
                foreach ($items as $item) {
                $subtotal = $item->precio * $item->cantidad;
                $total += $subtotal;
                }

                $totalConDescuento = $total - ($total * ($descuento / 100));
                $descuento = $total * ($descuento / 100);
                $factura->subtotal=$totalConDescuento;
                $factura->descuento = $descuento;
                $factura->save();
                echo "Total: $total\n";
                echo "Total con descuento: $totalConDescuento\n";
            } catch (\Throwable $th) {
                echo $factura->id."<br>";
            }

            
        }
        return Command::SUCCESS;
    }
}
