<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PihakSidang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pihak_sidang';

    protected $fillable = [
        'jadwal_sidang_id',
        'nama',
        'nomor_hp',
        'status_pihak',
    ];

    /**
     * Relasi ke Jadwal Sidang
     */
    public function jadwalSidang(): BelongsTo
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_sidang_id');
    }

    /**
     * Relasi ke Kehadiran (satu ke satu/nol)
     */
    public function kehadiran(): HasOne
    {
        return $this->hasOne(Kehadiran::class, 'pihak_sidang_id');
    }
}
