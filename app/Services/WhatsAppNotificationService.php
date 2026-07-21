<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->url = config('services.fonnte.url');
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
        if (empty($this->token)) {
            Log::warning("WhatsAppNotificationService: Kredensial Fonnte belum dikonfigurasi lengkap di .env.");
            return false;
        }

        $footer = "\n*" . config('app.name', 'SI-OCID') . " | Sistem Informasi Terpadu Absensi Sidang* \n*PTUN BANDAR LAMPUNG* | (C) " . date('Y');
        $formattedMessage = $message . "\n-----------------------------------------\n_Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan._\n" . $footer;

        try {
            $targets = explode(',', $target);
            $formattedTargets = [];

            foreach ($targets as $t) {
                $cleanTarget = trim($t);
                if (empty($cleanTarget)) {
                    continue;
                }

                // Clean and format target number
                $numericTarget = preg_replace('/[^0-9]/', '', $cleanTarget);
                if (empty($numericTarget)) {
                    Log::warning("WhatsAppNotificationService: Nomor target '{$cleanTarget}' tidak valid.");
                    continue;
                }

                // If number starts with 0, convert to 62
                if (str_starts_with($numericTarget, '0')) {
                    $numericTarget = '62' . substr($numericTarget, 1);
                }
                
                $formattedTargets[] = $numericTarget;
            }

            if (empty($formattedTargets)) {
                Log::warning("WhatsAppNotificationService: Tidak ada nomor target yang valid.");
                return false;
            }

            $targetString = implode(',', $formattedTargets);

            Log::info("WhatsAppNotificationService: Mengirim WhatsApp menggunakan Fonnte HTTP Client ke: {$targetString}");

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url ?: 'https://api.fonnte.com/send', [
                'target' => $targetString,
                'message' => $formattedMessage,
            ]);

            $status = $response->json('status');
            if ($response->successful() && ($status === true || $status === 'true')) {
                Log::info("WhatsAppNotificationService: Notifikasi WhatsApp berhasil dikirim ke: {$targetString}");
                return true;
            } else {
                Log::warning("WhatsAppNotificationService: Notifikasi WhatsApp gagal dikirim ke: {$targetString}. Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WhatsAppNotificationService: Exception saat mengirim WhatsApp ke {$target}. Error: " . $e->getMessage());
            return false;
        }
    }
}
