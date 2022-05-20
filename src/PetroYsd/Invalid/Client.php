<?php

namespace XuanChen\PetroYsd\Invalid;

use Exception;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class Client extends BaseClient
{

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
            $this->setActionType('pfRevokeCoupon');

            $coupon = PetroYsdCoupon::query()->where('couponNo', $this->params['cxcouponNo'])->first();

            if (! $coupon) {
                throw new PetroYsdException('未查询到优惠券信息');
            }

            $this->app->client->invalidCoupon($this->getPostData());//获取优惠券

            $this->app->callback->setInData($this->app->client->resData)->start();//解密
            //入库
            $this->app->log->setData([
                'in_source'  => $this->app->client->postData,
                'out_source' => $this->app->callback->inData
            ])->start();

            $coupon->update([
                'status' => PetroYsdCoupon::STATUS_REPEAL
            ]);

            return $this->app->callback->truthfulData;

        } catch (\Exception $e) {
            if ($this->app->client->postData) {
                $this->app->log->setData([
                    'in_source'  => $this->app->client->postData,
                    'out_source' => ['error' => $e->getMessage()]
                ])->start();
            }

            throw new PetroYsdException($e->getMessage());
        }


    }

}
