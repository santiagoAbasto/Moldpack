<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Carrito extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $pedido_carrito;
    public $file;
    public function __construct($pedido_carrito,$file)
    {
        $this->file=$file;
        $this->pedido_carrito=$pedido_carrito;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = "Pedido N° ".$this->pedido_carrito->numeroPedido;
        $return = $this
            ->subject( $title )
            ->view('emails.Carrito')
            ->with( 'pedido_carrito',$this->pedido_carrito );
        if(!empty( $this->file)) {
            $return = $return->attach($this->file->getRealPath(),
            [
                'as' => $this->file->getClientOriginalName(),
                'mime' => $this->file->getClientMimeType(),
            ]);
        }
        return $return;
    }
    
    public function descuentoGlobal(){
        $desc = $this->descuento;
        if($desc == 0){
            return 1;
        }
        $descuento = 100 - $desc;
        $descuento = $descuento / 100;
        return $descuento;
    }
}
