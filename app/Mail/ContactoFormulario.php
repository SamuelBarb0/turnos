<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\ContactoFormulario;

class ContactoFormulario extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    /**
     * Create a new message instance.
     */
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->datos['asunto'] ?? 'Nuevo mensaje de contacto')
                    ->view('emails.contacto')
                    ->with('datos', $this->datos);
    }
}
