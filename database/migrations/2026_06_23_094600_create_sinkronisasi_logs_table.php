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
        Schema::create('sinkronisasi_log', function (Blueprint $table) {
            $table->id();
            $table->timestamp('waktu_sinkronisasi');
            $table->integer('jumlah_data');
            $table->string('status'); // berhasil, gagal
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinkronisasi_log');
    }
};
