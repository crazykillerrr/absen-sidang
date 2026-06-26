<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PanggilanSidangMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $pihak;
    public $perkara;

    /**
     * Create a new message instance.
     */
    public function __construct($jadwal, $pihak)
    {
        $this->jadwal = $jadwal;
        $this->pihak = $pihak;
        $this->perkara = $jadwal->perkara;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[PANGGILAN PERSIDANGAN] Mohon Segera Memasuki Ruang Sidang - Perkara No. {$this->perkara->nomor_perkara}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.panggilan_sidang',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
