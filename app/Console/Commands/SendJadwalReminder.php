<?php

namespace App\Console\Commands;

use App\Models\JadwalSidang;
use App\Models\Notifikasi;
use App\Services\WhatsAppNotificationService;
use App\Mail\PengingatSidangMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendJadwalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat H-1 jadwal sidang ke para pihak lewat WhatsApp dan Email';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $waService): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $this->info("Mencari jadwal sidang untuk tanggal {$tomorrow}...");

        $jadwals = JadwalSidang::whereDate('tanggal_sidang', $tomorrow)
            ->with(['perkara', 'pihakSidangs', 'ruangSidang'])
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info("Tidak ada jadwal sidang untuk esok hari.");
            return Command::SUCCESS;
        }

        $waCount = 0;
        $emailCount = 0;

        foreach ($jadwals as $jadwal) {
            $perkara = $jadwal->perkara;
            $pihaks = $jadwal->pihakSidangs;

            foreach ($pihaks as $pihak) {
                // Format tanggal & jam
                $tanggal = $jadwal->tanggal_sidang instanceof Carbon 
                    ? $jadwal->tanggal_sidang->format('d-m-Y') 
                    : Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');
                $jam = substr($jadwal->jam_sidang, 0, 5);

                // Kirim WhatsApp jika nomor_hp diisi
                if (!empty($pihak->nomor_hp)) {
                    $nomorPerkara = $perkara?->nomor_perkara ?? '-';
                    $agendaSidang = $jadwal->agenda_sidang ?? '-';
                    $namaRuang = $jadwal->ruangSidang?->nama_ruang ?? '-';
                    $waMessage = "PENGINGAT SIDANG H-1: Diingatkan kembali bahwa jadwal sidang Anda untuk perkara nomor {$nomorPerkara} dengan agenda {$agendaSidang} akan dilaksanakan pada esok hari ($tanggal pukul $jam WIB) di {$namaRuang}. Harap hadir 30 menit sebelum sidang dimulai. Terima kasih.";
                    
                    $waStatus = $waService->sendNotification($pihak->nomor_hp, $waMessage);
                    
                    Notifikasi::create([
                        'jadwal_sidang_id' => $jadwal->id,
                        'jenis' => 'WhatsApp',
                        'status_kirim' => $waStatus ? 'terkirim' : 'gagal',
                        'waktu_kirim' => Carbon::now()
                    ]);
                    if ($waStatus) {
                        $waCount++;
                    }
                }

                // Kirim Email jika email diisi
                if (!empty($pihak->email)) {
                    try {
                        Mail::to($pihak->email)->send(new PengingatSidangMail($jadwal, $pihak));
                        
                        Notifikasi::create([
                            'jadwal_sidang_id' => $jadwal->id,
                            'jenis' => 'Email',
                            'status_kirim' => 'terkirim',
                            'waktu_kirim' => Carbon::now()
                        ]);
                        $emailCount++;
                    } catch (\Exception $e) {
                        Log::error("SendJadwalReminder: Gagal kirim email ke {$pihak->email}. Error: " . $e->getMessage());
                        Notifikasi::create([
                            'jadwal_sidang_id' => $jadwal->id,
                            'jenis' => 'Email',
                            'status_kirim' => 'gagal',
                            'waktu_kirim' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        $this->info("Selesai mengirim pengingat. WhatsApp terkirim: {$waCount}, Email terkirim: {$emailCount}");
        return Command::SUCCESS;
    }
}
