<?php

namespace XuanChen\PetroYsd;

use Pimple\Container;

/**
 * Class Application.
 *
 * @method static Grant\Client Grant
 * @method static Detail\Client Detail
 * @method static Kernel\Client Client
 * @method static Log\Client Log
 * @method static Invalid\Client Invalid
 * @method static Notice\Client Notice
 * @method static GrantNotice\Client GrantNotice
 * @method static Query\Client Query
 * @method static Rsa\Client Rsa
 */
class Application extends Container
{
    /**
     * 要注册的服务类.
     *
     * @var array
     */
    protected array $providers = [
        Grant\ServiceProvider::class,
        Detail\ServiceProvider::class,
        Kernel\ServiceProvider::class,
        Log\ServiceProvider::class,
        Invalid\ServiceProvider::class,
        Notice\ServiceProvider::class,
        Query\ServiceProvider::class,
        Rsa\ServiceProvider::class,
        GrantNotice\ServiceProvider::class,
    ];

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this['config'] = static function () {
            return config('petro_ysd');
        };

        $this->registerProviders();
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Notes: 获取服务
     *
     * @Author: 玄尘
     * @Date: 2022/2/21 13:22
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->offsetGet(strtolower($name));
    }

    /**
     * Notes: 获取服务
     *
     * @Author: 玄尘
     * @Date: 2022/2/21 13:22
     * @param  string  $name
     * @param $arguments
     * @return mixed
     */
    public function __call(string $name, $arguments)
    {
        return $this->offsetGet(strtolower($name));
    }
}
