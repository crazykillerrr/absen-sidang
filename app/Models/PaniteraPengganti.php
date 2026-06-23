<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaniteraPengganti extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'panitera_pengganti';

    protected $fillable = [
        'nama',
        'nomor_whatsapp',
        'email',
    ];

    /**
     * Relasi ke perkara (banyak ke banyak via penugasan_pp)
     */
    public function perkaras(): BelongsToMany
    {
        return $this->belongsToMany(Perkara::class, 'penugasan_pp', 'panitera_pengganti_id', 'perkara_id')
            ->withPivot('id')
            ->withTimestamps();
    }
}
