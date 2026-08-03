<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DondeComprarMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $dataRequest;    
    public function __construct($dataRequest)
    {        
        $this->dataRequest=$dataRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = "Contacto revendedor";
        $return = $this
            ->subject( $title )
            ->view('emails.DondeComprar')
            ->with( $this->dataRequest );
        
        return $return;
    }
}
