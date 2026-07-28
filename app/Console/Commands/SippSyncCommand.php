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
    protected $signature = 'sipp:sync {--days-before=0} {--days-ahead=10}';

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
        $daysBefore = (int) $this->option('days-before');
        $daysAhead = (int) $this->option('days-ahead');

        $periodText = $daysBefore > 0 
            ? "{$daysBefore} hari lalu s/d {$daysAhead} hari ke depan" 
            : "hari ini s/d {$daysAhead} hari ke depan";
        $this->info("Memulai sinkronisasi jadwal sidang SIPP ({$periodText})...");

        try {
            $count = $service->sync(null, $daysBefore, $daysAhead);
            $this->info("Sinkronisasi berhasil! Menambahkan/memperbarui {$count} jadwal sidang.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Sinkronisasi gagal: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
