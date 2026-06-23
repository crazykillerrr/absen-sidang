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
        Schema::create('penugasan_pp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkara_id')->constrained('perkara')->onDelete('cascade');
            $table->foreignId('panitera_pengganti_id')->constrained('panitera_pengganti')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_pp');
    }
};
