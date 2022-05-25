<?php

namespace XuanChen\PetroYsd\Rsa;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['rsa'] = static function ($app) {
            return new Client($app);
        };
    }
}
