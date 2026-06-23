<?php

namespace App\Repositories\Eloquent;

use App\Models\QrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;

class QrCodeRepository extends BaseRepository implements QrCodeRepositoryInterface
{
    public function __construct(QrCode $model)
    {
        parent::__construct($model);
    }
}
