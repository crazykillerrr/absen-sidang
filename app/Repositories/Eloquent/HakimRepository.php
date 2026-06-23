<?php

namespace App\Repositories\Eloquent;

use App\Models\Hakim;
use App\Repositories\Contracts\HakimRepositoryInterface;

class HakimRepository extends BaseRepository implements HakimRepositoryInterface
{
    public function __construct(Hakim $model)
    {
        parent::__construct($model);
    }
}
