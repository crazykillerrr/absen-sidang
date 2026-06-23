<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkara_id')->constrained('perkara')->onDelete('cascade');
            $table->foreignId('ruang_sidang_id')->constrained('ruang_sidang')->onDelete('cascade');
            $table->string('agenda_sidang'); // Dismissal, Pemeriksaan Persiapan, Pemeriksaan Bukti Surat, Pemeriksaan Bukti Saksi, Pemeriksaan Bukti Ahli, Eksekusi
            $table->date('tanggal_sidang');
            $table->time('jam_sidang');
            $table->string('jenis_sidang'); // Offline, Online
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_sidang');
    }
};
