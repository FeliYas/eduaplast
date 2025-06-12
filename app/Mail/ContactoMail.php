<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Log;

class ContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    public function __construct($datos)
    {
        $this->datos = $datos ?? [];

        // Log para depurar los datos recibidos
        Log::info('Datos recibidos en ContactoMail:', $this->datos);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje de contacto',
            from: new Address('no-reply@eduaplast.com', ($this->datos['nombre'] ?? '') . ' ' . ($this->datos['apellido'] ?? '')),

        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contacto',
            with: [
                'datos' => $this->datos ?? [],
            ],
        );
    }
}
