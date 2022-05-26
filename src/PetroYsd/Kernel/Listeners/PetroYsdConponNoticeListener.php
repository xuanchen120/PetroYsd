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
     *  本时生活 2 核销 3 作废  1撤销
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
            $client = new Client();

            $response = $client->request('post', $url, [
                'timeout' => 30,
                'query'   => [
                    'code'   => $coupon->couponCode,
                    'status' => 3,
                ],
            ]);
            
            if ($response->getStatusCode() != 200) {
                $result = json_decode($response->getBody()->getContents(), true);
                throw new RuntimeException($result);

            }
        }
    }

}
