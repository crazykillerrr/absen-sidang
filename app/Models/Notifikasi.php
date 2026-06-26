<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'jadwal_sidang_id',
        'jenis',
        'status_kirim',
        'waktu_kirim',
    ];

    protected $casts = [
        'waktu_kirim' => 'datetime',
    ];

    /**
     * Relasi ke Jadwal Sidang
     */
    public function jadwalSidang(): BelongsTo
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_sidang_id')->withTrashed();
    }
}
