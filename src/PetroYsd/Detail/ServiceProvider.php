<?php

namespace XuanChen\PetroYsd\Detail;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['detail'] = static function ($app) {
            return new Client($app);
        };
    }
}
