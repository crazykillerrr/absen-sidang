<?php

namespace App\Repositories\Eloquent;

use App\Models\Kehadiran;
use App\Repositories\Contracts\KehadiranRepositoryInterface;

class KehadiranRepository extends BaseRepository implements KehadiranRepositoryInterface
{
    public function __construct(Kehadiran $model)
    {
        parent::__construct($model);
    }
}
