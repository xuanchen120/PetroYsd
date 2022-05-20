<?php

namespace XuanChen\PetroYsd\Kernel\Event;


use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

/**
 * 核券之后的回调
 */
class CouponNotice
{

    public $petro_coupon;

    public function __construct(PetroYsdCoupon $petroCoupon)
    {
        $this->petro_coupon = $petroCoupon;
    }

}
