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
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->string('jenis_perkara')->nullable();
            $table->text('pihak')->nullable();
            $table->string('sidang_keliling')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropColumn(['jenis_perkara', 'pihak', 'sidang_keliling']);
        });
    }
};
