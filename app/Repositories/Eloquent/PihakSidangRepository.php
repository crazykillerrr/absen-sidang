<?php

namespace App\Repositories\Eloquent;

use App\Models\PihakSidang;
use App\Repositories\Contracts\PihakSidangRepositoryInterface;

class PihakSidangRepository extends BaseRepository implements PihakSidangRepositoryInterface
{
    public function __construct(PihakSidang $model)
    {
        parent::__construct($model);
    }
}
