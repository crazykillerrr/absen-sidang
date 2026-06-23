<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanPp extends Model
{
    use HasFactory;

    protected $table = 'penugasan_pp';

    protected $fillable = [
        'perkara_id',
        'panitera_pengganti_id',
    ];

    public function perkara(): BelongsTo
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    public function paniteraPengganti(): BelongsTo
    {
        return $this->belongsTo(PaniteraPengganti::class, 'panitera_pengganti_id');
    }
}
