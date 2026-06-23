<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiSidangMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $perkara;
    public $waktu;

    /**
     * Create a new message instance.
     */
    public function __construct($jadwal, $perkara, $waktu)
    {
        $this->jadwal = $jadwal;
        $this->perkara = $perkara;
        $this->waktu = $waktu;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[MONITORING PERSIDANGAN PTUN] Kehadiran Sidang Lengkap - Perkara No. {$this->perkara->nomor_perkara}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi_sidang',
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
