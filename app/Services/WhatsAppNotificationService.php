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
     * Kirim WhatsApp ke nomor target
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

        $footer = "\n*" . config('app.name', 'SI-OCID') . " | Sistem Informasi Terpadu Absensi Sidang* \n*PTUN BANDAR LAMPUNG* | (C) " . date('Y');
        $formattedMessage = $message . "\n-----------------------------------------\n_Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan._\n" . $footer;

        try {
            $targets = explode(',', $target);
            $success = true;

            foreach ($targets as $t) {
                $cleanTarget = trim($t);
                if (empty($cleanTarget)) {
                    continue;
                }

                // Clean and format target number
                $numericTarget = preg_replace('/[^0-9]/', '', $cleanTarget);
                if (empty($numericTarget)) {
                    Log::warning("WhatsAppNotificationService: Nomor target '{$cleanTarget}' tidak valid.");
                    $success = false;
                    continue;
                }

                // If number starts with 0, convert to 62
                if (str_starts_with($numericTarget, '0')) {
                    $numericTarget = '62' . substr($numericTarget, 1);
                }
                
                $to = 'whatsapp:+' . $numericTarget;

                Log::info("WhatsAppNotificationService: Mengirim WhatsApp menggunakan Twilio HTTP Client ke: {$to}");

                $response = Http::withBasicAuth($this->sid, $this->token)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                        'To' => $to,
                        'From' => $this->from,
                        'Body' => $formattedMessage,
                    ]);

                if ($response->successful()) {
                    Log::info("WhatsAppNotificationService: Notifikasi WhatsApp berhasil dikirim ke: {$cleanTarget}");
                } else {
                    Log::warning("WhatsAppNotificationService: Notifikasi WhatsApp gagal dikirim ke: {$cleanTarget}. Response: " . $response->body());
                    $success = false;
                }
            }

            return $success;
        } catch (\Exception $e) {
            Log::error("WhatsAppNotificationService: Exception saat mengirim WhatsApp ke {$target}. Error: " . $e->getMessage());
            return false;
        }
    }
}
