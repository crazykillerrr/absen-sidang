<?php

namespace App\Repositories\Eloquent;

use App\Models\RuangSidang;
use App\Repositories\Contracts\RuangSidangRepositoryInterface;

class RuangSidangRepository extends BaseRepository implements RuangSidangRepositoryInterface
{
    public function __construct(RuangSidang $model)
    {
        parent::__construct($model);
    }
}
