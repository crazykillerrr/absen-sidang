<?php

namespace App\Repositories\Eloquent;

use App\Models\PaniteraPengganti;
use App\Repositories\Contracts\PaniteraPenggantiRepositoryInterface;

class PaniteraPenggantiRepository extends BaseRepository implements PaniteraPenggantiRepositoryInterface
{
    public function __construct(PaniteraPengganti $model)
    {
        parent::__construct($model);
    }
}
