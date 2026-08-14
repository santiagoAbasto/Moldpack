<?php

namespace App\Mail;

use App\Models\CarritoAbandonado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarritoAbandonadoReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $carritoAbandonado;

    public function __construct(CarritoAbandonado $carritoAbandonado)
    {
        $this->carritoAbandonado = $carritoAbandonado;
    }

    public function build()
    {
        return $this
            ->subject('Tenes productos pendientes en tu carrito Moldpack')
            ->view('emails.CarritoAbandonadoReminder')
            ->with('carritoAbandonado', $this->carritoAbandonado);
    }
}
