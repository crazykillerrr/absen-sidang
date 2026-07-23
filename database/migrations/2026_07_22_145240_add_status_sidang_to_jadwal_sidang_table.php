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
            $table->string('status_sidang')->default('belum_dimulai')->after('sumber_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropColumn('status_sidang');
        });
    }
};
