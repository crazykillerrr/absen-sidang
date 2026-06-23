<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuangSidang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruang_sidang';

    protected $fillable = [
        'nama_ruang',
        'jenis_ruang',
    ];

    /**
     * Relasi ke Jadwal Sidang (satu ke banyak)
     */
    public function jadwalSidangs(): HasMany
    {
        return $this->hasMany(JadwalSidang::class, 'ruang_sidang_id');
    }
}
