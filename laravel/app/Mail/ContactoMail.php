<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $dataRequest;
    public $file;
    public function __construct($dataRequest,$file)
    {
        $this->file=$file;
        $this->dataRequest=$dataRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = "Contacto";
        $return = $this
            ->subject( $title )
            ->view('emails.Contacto')
            ->with( $this->dataRequest );
        if(!empty( $this->file)) {
            $return = $return->attach($this->file->getRealPath(),
            [
                'as' => $this->file->getClientOriginalName(),
                'mime' => $this->file->getClientMimeType(),
            ]);
        }
        return $return;
    }
}
