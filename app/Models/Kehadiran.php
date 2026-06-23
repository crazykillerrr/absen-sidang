<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'pihak_sidang_id',
        'waktu_hadir',
        'status_hadir',
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
    ];

    /**
     * Relasi ke Pihak Sidang
     */
    public function pihakSidang(): BelongsTo
    {
        return $this->belongsTo(PihakSidang::class, 'pihak_sidang_id');
    }
}
