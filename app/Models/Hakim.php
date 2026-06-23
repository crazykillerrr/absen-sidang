<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hakim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hakim';

    protected $fillable = [
        'nama',
        'nomor_whatsapp',
        'email',
    ];

    /**
     * Relasi ke perkara (banyak ke banyak via majelis_hakim)
     */
    public function perkaras(): BelongsToMany
    {
        return $this->belongsToMany(Perkara::class, 'majelis_hakim', 'hakim_id', 'perkara_id')
            ->withPivot('id', 'jabatan')
            ->withTimestamps();
    }
}
