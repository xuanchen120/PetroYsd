<?php

namespace XuanChen\PetroYsd\Kernel\Models;

use App\Models\Model;

class PetroYsdLog extends Model
{
    const  TYPE_GRANT   = 'grant';
    const  TYPE_QUERY   = 'detail';
    const  TYPE_DESTORY = 'destroy';

    const  TYPES = [
        self::TYPE_GRANT   => '发券',
        self::TYPE_QUERY   => '查询',
        self::TYPE_DESTORY => '作废',
    ];

    public $casts = [
        'in_source'  => 'json',
        'out_source' => 'json',
    ];
}
