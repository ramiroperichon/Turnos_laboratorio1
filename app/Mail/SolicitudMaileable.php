<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use UserServices;

class SolicitudMaileable extends Mailable
{
    use Queueable, SerializesModels;

    protected $hora_inicio;
    protected $hora_fin;
    protected $fecha;
    protected $estado;
    protected $name;
    protected $precio;

    public function __construct($hora_inicio, $hora_fin, $fecha, $estado, $name, $precio)
    {
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->name = $name;
        $this->precio = $precio;

    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $user = auth()->user();

        return new Envelope(
            from: new Address($user->email, $user->name),
            subject: 'Solicitud Maileable',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.respuestaSolicitud',
            with: [
                'hora_inicio'=> $this->hora_inicio,
                'hora_fin'=> $this->hora_fin,
                'fecha'=> $this->fecha,
                'estado'=> $this->estado,
                'name'=> $this->name,
                'precio'=> $this->precio

            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
