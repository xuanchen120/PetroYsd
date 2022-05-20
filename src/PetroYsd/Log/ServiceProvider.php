<?php

namespace XuanChen\PetroYsd\Log;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['log'] = static function ($app) {
            return new Client($app);
        };
    }
}
