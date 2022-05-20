<?php

namespace XuanChen\PetroYsd\Notice;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['notice'] = static function ($app) {
            return new Client($app);
        };
    }
}
