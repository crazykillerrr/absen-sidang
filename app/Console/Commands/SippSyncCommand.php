<?php

namespace App\Console\Commands;

use App\Services\SippSyncService;
use Illuminate\Console\Command;

class SippSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipp:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi jadwal sidang dari SIPP PTUN Bandar Lampung';

    /**
     * Execute the console command.
     */
    public function handle(SippSyncService $service): int
    {
        $this->info('Memulai sinkronisasi jadwal sidang SIPP...');

        try {
            $count = $service->sync();
            $this->info("Sinkronisasi berhasil! Menambahkan/memperbarui {$count} jadwal sidang.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Sinkronisasi gagal: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
