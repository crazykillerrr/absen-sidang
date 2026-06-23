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
            $table->string('sumber_data')->default('SIPP');
            $table->timestamp('terakhir_sinkron')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropColumn(['sumber_data', 'terakhir_sinkron']);
        });
    }
};
