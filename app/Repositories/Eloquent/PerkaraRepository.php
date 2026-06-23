<?php

namespace App\Repositories\Eloquent;

use App\Models\Perkara;
use App\Repositories\Contracts\PerkaraRepositoryInterface;

class PerkaraRepository extends BaseRepository implements PerkaraRepositoryInterface
{
    public function __construct(Perkara $model)
    {
        parent::__construct($model);
    }
}
