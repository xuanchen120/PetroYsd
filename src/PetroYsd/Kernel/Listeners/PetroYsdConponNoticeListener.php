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
       

    }

}
