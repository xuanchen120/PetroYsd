<?php

namespace XuanChen\PetroYsd\Kernel\Models;

use App\Models\Model;

class PetroYsdCoupon extends Model
{
    const STATUS_INIT   = 1;
    const STATUS_CANCEL = 2;
    const STATUS_USED   = 4;

    const STATUS = [
        self::STATUS_INIT   => '未使用',
        self::STATUS_CANCEL => '已作废',
        self::STATUS_USED   => '已使用',
    ];

    const PRODUCT_TYPE_ZSYTJQ  = 'ZSYTJQ';
    const PRODUCT_TYPE_LNZSY   = 'LNZSY';
    const PRODUCT_TYPE_SINOPEC = 'LNZSY';

    const PRODUCT_TYPES = [
        self::PRODUCT_TYPE_ZSYTJQ  => '石油',
        self::PRODUCT_TYPE_LNZSY   => '辽宁中石油',
        self::PRODUCT_TYPE_SINOPEC => '中石化',
    ];

    public function log()
    {
        return $this->belongsTo(PetroYsdLog::class, 'petro_log_id');
    }
}
