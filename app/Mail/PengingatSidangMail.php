<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengingatSidangMail extends Mailable
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
        $this->perkara = $jadwal?->perkara;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $nomorPerkara = $this->perkara?->nomor_perkara ?? '-';
        return new Envelope(
            subject: "[PENGINGAT SIDANG PTUN] Jadwal Sidang Esok Hari - Perkara No. {$nomorPerkara}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengingat_sidang',
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
