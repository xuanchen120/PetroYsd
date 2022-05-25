<?php

return [
    /**
     * 服务器地址
     */
    'base_uri'       => env('PETRO_YSD_BASE_URI', ''),

    /**
     * 渠道编码
     */
    'channelCode'    => env('PETRO_YSD_CHANNEL_CODE', ''),

    /**
     *商户秘钥
     */
    'private_key'    => env('PETRO_YSD_PRIVATE_KEY', ''),

    /**
     *商户秘钥
     */
    'public_key'     => env('PETRO_YSD_PUBLIC_KEY', ''),

    /**
     * 回调通知接口
     */
    'ysd_notice_url' => 'https://lifetest.ysd-bs.com/api/store/callback',
];
