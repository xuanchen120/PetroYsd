<?php

namespace XuanChen\PetroYsd\Query;

use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class Client extends BaseClient
{

    protected $path = '/channel/order/query';
    protected $type = 'query';

    public function start()
    {
        try {
            $this->params['sign'] = $this->getSign();

            $coupon = PetroYsdCoupon::query()->where('thirdOrderId', $this->params['thirdOrderId'])->exists();
            if (! $coupon) {
                throw new PetroYsdException('未查询到优惠券信息');
            }
            dump($this->params);
            $this->app->client->getGrantInfo($this->getPostData(), $this->path);//获取优惠券

            //入库
            $this->app->log->setData([
                'type'       => $this->type,
                'in_source'  => $this->app->client->postData,
                'out_source' => $this->app->client->resData
            ])->start();

            return $this->app->client->resData;

        } catch (\Exception $e) {
            $out_source = [$e->getMessage()];
            if ($this->app->client->resData) {
                $out_source = $this->app->client->resData;
            }
            $this->app->log->setData([
                'type'       => $this->type,
                'in_source'  => $this->params,
                'out_source' => $out_source
            ])->start();

            return $e->getMessage();
        }

    }

}
