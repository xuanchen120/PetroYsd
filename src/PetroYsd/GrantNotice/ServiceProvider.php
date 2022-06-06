<?php

namespace XuanChen\PetroYsd\GrantNotice;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['grantnotice'] = static function ($app) {
            return new Client($app);
        };
    }
}
