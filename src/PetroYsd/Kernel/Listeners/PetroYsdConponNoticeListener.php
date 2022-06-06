<?php

namespace XuanChen\PetroYsd\Kernel\Listeners;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use RuntimeException;
use XuanChen\PetroYsd\Kernel\Event\CouponNotice;

class PetroYsdConponNoticeListener implements ShouldQueue
{

    public $queue = 'LISTENER';

    /**
     * Handle the event.
     *  本时生活 0 未使用 2 核销 3 作废  1撤销
     *  本程序  1 未使用 2 已作废 4  已使用
     *
     * @param  CouponNotice  $event
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(CouponNotice $event)
    {
        $coupon = $event->petro_coupon;
        $url    = config('petro_ysd.ysd_notice_url');
        if ($url) {
            $client   = new Client();
            $status   = [
                            1 => 0,
                            2 => 3,
                            4 => 2,
                        ][$coupon->state];
            $response = $client->request('post', $url, [
                'timeout' => 30,
                'query'   => [
                    'code'   => $coupon->couponCode,
                    'status' => $status,
                ],
            ]);
            
            if ($response->getStatusCode() != 200) {
                $result = json_decode($response->getBody()->getContents(), true);
                throw new RuntimeException($result);

            }
        }
    }

}
