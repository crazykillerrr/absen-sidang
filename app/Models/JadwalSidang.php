<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalSidang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_sidang';

    protected $fillable = [
        'perkara_id',
        'ruang_sidang_id',
        'agenda_sidang',
        'tanggal_sidang',
        'jam_sidang',
        'jenis_sidang',
        'sumber_data',
        'terakhir_sinkron',
    ];

    protected $casts = [
        'tanggal_sidang' => 'date',
        'terakhir_sinkron' => 'datetime',
    ];

    /**
     * Relasi ke Perkara
     */
    public function perkara(): BelongsTo
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    /**
     * Relasi ke Ruang Sidang
     */
    public function ruangSidang(): BelongsTo
    {
        return $this->belongsTo(RuangSidang::class, 'ruang_sidang_id');
    }

    /**
     * Relasi ke Pihak Sidang (satu ke banyak)
     */
    public function pihakSidangs(): HasMany
    {
        return $this->hasMany(PihakSidang::class, 'jadwal_sidang_id');
    }

    /**
     * Relasi ke Notifikasi (satu ke banyak)
     */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'jadwal_sidang_id');
    }
}
