<?php

namespace XuanChen;

use Illuminate\Support\Facades\Facade;

/**
 * Class Petro.
 *
 * @method static PetroYsd\Grant\Client Grant
 * @method static PetroYsd\Detail\Client Detail
 * @method static PetroYsd\Kernel\Client Client
 * @method static PetroYsd\CallBack\Client CallBack
 * @method static PetroYsd\Invalid\Client Invalid
 * @method static PetroYsd\Notice\Client Notice
 */
class PetroYsd extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PetroYsd\Application::class;
    }
}
