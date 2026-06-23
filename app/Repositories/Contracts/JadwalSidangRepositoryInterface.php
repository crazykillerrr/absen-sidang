<?php

namespace App\Repositories\Contracts;

interface JadwalSidangRepositoryInterface extends RepositoryInterface
{
    public function getTodaySchedules();
    public function getActiveSchedulesForToday();
}
