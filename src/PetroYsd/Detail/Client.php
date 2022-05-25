<?php

namespace XuanChen\PetroYsd\Detail;

use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class Client extends BaseClient
{

    protected $path = '/channel/cardStatus';
    protected $type = 'detail';

    public function start()
    {
        try {
            $this->params['sign'] = $this->getSign();

            $coupon = PetroYsdCoupon::query()->where('couponId', $this->params['couponId'])->first();
            if (! $coupon) {
                throw new PetroYsdException('未查询到优惠券信息');
            }

            $this->app->client->getGrantInfo($this->getPostData(), $this->path);//获取优惠券

            $this->addLog();//插入日志

            return $this->app->client->resData;

        } catch (\Exception $e) {
            $this->addLog($e->getMessage());
            return $e->getMessage();
        }

    }

}
