<?php

namespace App\Repositories\Eloquent;

use App\Models\JadwalSidang;
use App\Repositories\Contracts\JadwalSidangRepositoryInterface;
use Carbon\Carbon;

class JadwalSidangRepository extends BaseRepository implements JadwalSidangRepositoryInterface
{
    public function __construct(JadwalSidang $model)
    {
        parent::__construct($model);
    }

    public function getTodaySchedules()
    {
        return $this->model->whereDate('tanggal_sidang', Carbon::today())
            ->with(['perkara.hakims', 'perkara.paniteraPenggantis', 'ruangSidang', 'pihakSidangs.kehadiran'])
            ->orderBy('jam_sidang', 'asc')
            ->get();
    }

    public function getActiveSchedulesForToday()
    {
        return $this->model->whereDate('tanggal_sidang', Carbon::today())
            ->with(['perkara', 'ruangSidang', 'pihakSidangs.kehadiran'])
            ->orderBy('jam_sidang', 'asc')
            ->get();
    }
}
