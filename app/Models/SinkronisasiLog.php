<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinkronisasiLog extends Model
{
    use HasFactory;

    protected $table = 'sinkronisasi_log';

    protected $fillable = [
        'waktu_sinkronisasi',
        'jumlah_data',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'waktu_sinkronisasi' => 'datetime',
    ];
}
