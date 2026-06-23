<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN');
        $this->url = env('FONNTE_URL', 'https://api.fonnte.com/send');
    }

    /**
     * Kirim WhatsApp ke nomor target
     *
     * @param string $target Target nomor handphone (bisa koma-terpisah)
     * @param string $message Isi pesan
     * @return bool Status pengiriman
     */
    public function sendNotification(string $target, string $message): bool
    {
        if (empty($this->token) || $this->token === 'your_fonnte_token_here') {
            Log::warning("WhatsAppNotificationService: Fonnte token belum dikonfigurasi di .env. Notifikasi batal dikirim ke: {$target}");
            return false;
        }

        try {
            // Fonnte menggunakan payload form-data / x-www-form-urlencoded
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->url, [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsAppNotificationService: Notifikasi WhatsApp berhasil dikirim ke: {$target}");
                return true;
            }

            Log::error("WhatsAppNotificationService: Gagal mengirim WhatsApp ke {$target}. HTTP Status: " . $response->status() . " Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppNotificationService: Exception saat mengirim WhatsApp ke {$target}. Error: " . $e->getMessage());
            return false;
        }
    }
}
