<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

$to = readline("Masukkan email tujuan tes: ");
if (empty($to)) {
    echo "Email tidak boleh kosong.\n";
    exit(1);
}

try {
    echo "Mengirim email ke {$to}...\n";
    Mail::raw("Uji coba pengiriman email dari aplikasi Absen Sidang PTUN. Email ini dikirim pada " . date('Y-m-d H:i:s') . " untuk memverifikasi deliverabilitas email.", function($message) use ($to) {
        $message->to($to)
                ->subject("Tes Pengiriman Email PTUN - " . date('d/m/Y'));
    });
    echo "Sukses! Email berhasil dikirim. Silakan cek Inbox atau folder Spam Anda.\n";
} catch (\Exception $e) {
    echo "Gagal mengirim email: " . $e->getMessage() . "\n";
}
