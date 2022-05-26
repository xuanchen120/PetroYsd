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
     * 本时生活回调地区
     */
    'ysd_notice_url' => env('PETRO_YSD_CARD_NOTICE_URL', ''),

];
