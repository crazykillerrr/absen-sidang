<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
     * Relasi ke Jadwal Sidang (satu ke banyak)
     */
    public function jadwalSidangs(): HasMany
    {
        return $this->hasMany(JadwalSidang::class, 'perkara_id');
    }
}
