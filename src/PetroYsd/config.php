<?php

return [
    /**
     * 服务器地址
     */
    'base_uri'      => env('PETRO_BASE_URI', ''),
    /**
     * 字符集
     */
    'characterSet'  => '00',
    /**
     * 商户编号
     */
    'strVendorCode' => env('PETRO_STR_VENDOR_CODE', ''),
    /**
     *商户秘钥
     */
    'merchantKey'   => env('PETRO_MERCHANT_KEY', ''),
    /**
     * 单个电子券秘钥
     */
    'couponKey'     => env('PETRO_COUPON_KEY', ''),

    'ysd_notice_url' => 'https://lifetest.ysd-bs.com/api/store/callback',
];
