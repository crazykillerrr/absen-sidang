<?php

namespace App\Repositories\Eloquent;

use App\Models\Notifikasi;
use App\Repositories\Contracts\NotifikasiRepositoryInterface;

class NotifikasiRepository extends BaseRepository implements NotifikasiRepositoryInterface
{
    public function __construct(Notifikasi $model)
    {
        parent::__construct($model);
    }
}
