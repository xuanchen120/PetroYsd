<?php

namespace XuanChen\PetroYsd;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use XuanChen\PetroYsd\Kernel\Event\CouponNotice;
use XuanChen\PetroYsd\Kernel\Listeners\PetroYsdConponNoticeListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        CouponNotice::class => [
            PetroYsdConponNoticeListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
