<?php

namespace XuanChen\PetroYsd\Kernel\Models;

use App\Models\Model;

class PetroYsdCoupon extends Model
{
    const STATUS_INIT   = 1;
    const STATUS_UNSED  = 2;
    const STATUS_USED   = 3;
    const STATUS_NOTICE = 4;
    const STATUS_PAST   = 5;
    const STATUS_REPEAL = 7;
    const STATUS_LOSE   = 99;

    const STATUS = [
        self::STATUS_INIT   => '出始化',
        self::STATUS_UNSED  => '待使用',
        self::STATUS_USED   => '已使用',
        self::STATUS_NOTICE => '已通知',
        self::STATUS_PAST   => '已过期',
        self::STATUS_REPEAL => '撤销',
        self::STATUS_LOSE   => '失效',
    ];

    public $casts = [
        'goodsInfo' => 'json'
    ];
}
