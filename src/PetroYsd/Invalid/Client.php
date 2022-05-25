<?php

namespace XuanChen\PetroYsd\Invalid;

use Exception;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class Client extends BaseClient
{

    protected $path = '/channel/order/destroy';
    protected $type = 'destroy';

    /**
     * Notes: 开始执行
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 14:05
     * @throws Exception
     */
    public function start()
    {
        try {

            $this->params['sign'] = $this->getSign();

            $coupon = PetroYsdCoupon::query()->where('couponId', $this->params['couponId'])->first();
            if (! $coupon) {
                throw new PetroYsdException('未查询到优惠券信息');
            }

            $this->app->client->getGrantInfo($this->getPostData(), $this->path);//获取优惠券

            $coupon->update([
                'state' => $this->client->resData['data']['state']
            ]);

            $this->addLog();

            return $this->app->client->resData;

        } catch (\Exception $e) {
            $this->addLog($e->getMessage());
            return $e->getMessage();
        }


    }

}
