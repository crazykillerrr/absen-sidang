<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MajelisHakim extends Model
{
    use HasFactory;

    protected $table = 'majelis_hakim';

    protected $fillable = [
        'perkara_id',
        'hakim_id',
        'jabatan',
    ];

    public function perkara(): BelongsTo
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    public function hakim(): BelongsTo
    {
        return $this->belongsTo(Hakim::class, 'hakim_id');
    }
}
