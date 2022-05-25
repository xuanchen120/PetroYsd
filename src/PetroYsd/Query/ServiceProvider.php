<?php

namespace XuanChen\PetroYsd\Query;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['query'] = static function ($app) {
            return new Client($app);
        };
    }
}
