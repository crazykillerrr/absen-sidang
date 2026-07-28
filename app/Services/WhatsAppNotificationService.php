<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.from');
    }

    /**
     * Kirim WhatsApp ke nomor target via Twilio API Gateway
     *
     * @param string $target Target nomor handphone (bisa koma-terpisah)
     * @param string $message Isi pesan
     * @return bool Status pengiriman
     */
    public function sendNotification(string $target, string $message): bool
    {
        if (empty($this->sid) || empty($this->token) || empty($this->from)) {
            Log::warning("WhatsAppNotificationService: Kredensial Twilio belum dikonfigurasi lengkap di .env.");
            return false;
        }

        $footer = "\n*" . config('app.name', 'SIPEKA') . " | Sistem Pemantauan Kehadiran* \n*PTUN BANDAR LAMPUNG* | (C) " . date('Y');
        $formattedMessage = $message . "\n-----------------------------------------\n_Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan._\n" . $footer;

        try {
            $targets = explode(',', $target);
            $formattedTargets = [];

            foreach ($targets as $t) {
                $cleanTarget = trim($t);
                if (empty($cleanTarget)) {
                    continue;
                }

                // Clean target number (keep digits)
                $numericTarget = preg_replace('/[^0-9]/', '', $cleanTarget);
                if (empty($numericTarget)) {
                    Log::warning("WhatsAppNotificationService: Nomor target '{$cleanTarget}' tidak valid.");
                    continue;
                }

                // If number starts with 0, convert to Indonesian country code 62
                if (str_starts_with($numericTarget, '0')) {
                    $numericTarget = '62' . substr($numericTarget, 1);
                }
                
                // Twilio WhatsApp recipient format: whatsapp:+628123456789
                $formattedTargets[] = 'whatsapp:+' . $numericTarget;
            }

            if (empty($formattedTargets)) {
                Log::warning("WhatsAppNotificationService: Tidak ada nomor target yang valid.");
                return false;
            }

            // Ensure sender format has whatsapp: prefix
            $fromNumber = $this->from;
            if (!str_starts_with($fromNumber, 'whatsapp:')) {
                if (!str_starts_with($fromNumber, '+')) {
                    $fromNumber = '+' . $fromNumber;
                }
                $fromNumber = 'whatsapp:' . $fromNumber;
            }

            $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";
            $allSuccess = true;
            $hasAtLeastOneSent = false;

            foreach ($formattedTargets as $to) {
                Log::info("WhatsAppNotificationService: Mengirim WhatsApp via Twilio ke: {$to}");

                $response = Http::withBasicAuth($this->sid, $this->token)
                    ->asForm()
                    ->post($url, [
                        'From' => $fromNumber,
                        'To' => $to,
                        'Body' => $formattedMessage,
                    ]);

                if ($response->successful() && empty($response->json('error_code'))) {
                    Log::info("WhatsAppNotificationService: Notifikasi WhatsApp Twilio berhasil dikirim ke: {$to}. Message SID: " . ($response->json('sid') ?? '-'));
                    $hasAtLeastOneSent = true;
                } else {
                    $allSuccess = false;
                    Log::warning("WhatsAppNotificationService: Notifikasi WhatsApp Twilio gagal dikirim ke: {$to}. Response: " . $response->body());
                }
            }

            return $hasAtLeastOneSent && $allSuccess;
        } catch (\Exception $e) {
            Log::error("WhatsAppNotificationService: Exception saat mengirim WhatsApp ke {$target}. Error: " . $e->getMessage());
            return false;
        }
    }
}
