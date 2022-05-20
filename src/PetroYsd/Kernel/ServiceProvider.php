<?php

namespace XuanChen\PetroYsd\Kernel;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['client'] = static function ($app) {
            return new Client($app);
        };
    }
}
