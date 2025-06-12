<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PresupuestoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;
    public $carrito;
    public $archivoPath;

    /**
     * Create a new message instance.
     */
    public function __construct($datos, $carrito, $archivoPath = null)
    {
        $this->datos = $datos ?? [];
        $this->carrito = $carrito ?? [];
        $this->archivoPath = $archivoPath ?? null;

        // Log para depurar los datos recibidos
        Log::info('Datos recibidos en PresupuestoMail:', $this->datos);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje de presupuesto',
            from: new Address('no-reply@eduaplast.com', ($this->datos['nombre'] ?? '')),

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.presupuesto',
            with: [
                'datos' => $this->datos,
                'carrito' => $this->carrito,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->archivoPath) {
            $attachments[] = new \Illuminate\Mail\Mailables\Attachment(
                storage_path('app/public/' . $this->archivoPath),
                basename($this->archivoPath)
            );
        }

        return $attachments;
    }
}
