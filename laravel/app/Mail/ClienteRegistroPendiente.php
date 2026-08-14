<?php

namespace App\Mail;

use App\Models\Cliente;
use App\Models\Contacto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClienteRegistroPendiente extends Mailable
{
    use Queueable, SerializesModels;

    public $cliente;
    public $contacto;

    public function __construct(Cliente $cliente, ?Contacto $contacto = null)
    {
        $this->cliente = $cliente;
        $this->contacto = $contacto;
    }

    public function build()
    {
        $mail = $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Moldpack - Solicitud de registro recibida')
            ->view('emails.ClienteRegistroPendiente');

        if ($this->contacto && filter_var($this->contacto->correo, FILTER_VALIDATE_EMAIL)) {
            $mail->replyTo($this->contacto->correo, 'Moldpack');
        }

        return $mail;
    }
}
