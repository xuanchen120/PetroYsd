<?php

namespace XuanChen\PetroYsd\CallBack;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['callback'] = static function ($app) {
            return new Client($app);
        };
    }
}
