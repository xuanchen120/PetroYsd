<?php

namespace XuanChen\PetroYsd\Invalid;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['invalid'] = static function ($app) {
            return new Client($app);
        };
    }
}
