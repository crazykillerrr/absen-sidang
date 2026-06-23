<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perkara extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perkara';

    protected $fillable = [
        'nomor_perkara',
        'tahun',
        'keterangan',
    ];

    /**
     * Relasi ke Hakim (banyak ke banyak via majelis_hakim)
     */
    public function hakims(): BelongsToMany
    {
        return $this->belongsToMany(Hakim::class, 'majelis_hakim', 'perkara_id', 'hakim_id')
            ->withPivot('id', 'jabatan')
            ->withTimestamps();
    }

    /**
     * Relasi ke Panitera Pengganti (banyak ke banyak via penugasan_pp)
     */
    public function paniteraPenggantis(): BelongsToMany
    {
        return $this->belongsToMany(PaniteraPengganti::class, 'penugasan_pp', 'perkara_id', 'panitera_pengganti_id')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * Relasi ke Jadwal Sidang (satu ke banyak)
     */
    public function jadwalSidangs(): HasMany
    {
        return $this->hasMany(JadwalSidang::class, 'perkara_id');
    }
}
