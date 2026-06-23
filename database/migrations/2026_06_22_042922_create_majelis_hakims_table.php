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
        Schema::create('majelis_hakim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkara_id')->constrained('perkara')->onDelete('cascade');
            $table->foreignId('hakim_id')->constrained('hakim')->onDelete('cascade');
            $table->string('jabatan'); // Ketua Majelis, Hakim Anggota
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majelis_hakim');
    }
};
