<?php

namespace XuanChen\PetroYsd\Grant;

use Exception;
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
            $this->setActionType('getCoupon');
            $this->app->client->getGrant($this->getPostData());//获取优惠券
            $this->app->callback->setInData($this->app->client->resData)->start();//解密
            $ticketDetail = $this->app->callback->truthfulData['ticketDetail']['0'];

            //入库
            $this->app->log->setData([
                'in_source'  => $this->client->postData,
                'out_source' => $this->app->callback->inData
            ])->start();

            $couponNo = $this->decrypt($ticketDetail['ticketNum'], $this->config['couponKey']);

            $coupon = PetroYsdCoupon::query()->where('couponNo', $couponNo)->first();
            if ($coupon) {
                return $coupon;
            }

            return PetroYsdCoupon::create([
                'petro_log_id'   => $this->app->log->source->id,
                'mobile'         => $this->mobile ?? '',
                'name'           => $ticketDetail['name'],
                'amount'         => $ticketDetail['amount'],
                'oilgCatName'    => $ticketDetail['oilgCatName'],
                'oillimitAmount' => $ticketDetail['oillimitAmount'],
                'effectiveTime'  => $ticketDetail['effectiveTime'],
                'expiredDate'    => $ticketDetail['expiredDate'],
                'requestCode'    => $ticketDetail['requestCode'],
                'isReuse'        => $ticketDetail['isReuse'],
                'catName'        => $ticketDetail['catName'],
                'glCatName'      => $ticketDetail['glCatName'],
                'oilglCatName'   => $ticketDetail['oilglCatName'],
                'couponNo'       => $couponNo,
            ]);

        } catch (\Exception $e) {
            $this->app->log->setData([
                'in_source'  => $this->app->client->postData,
                'out_source' => ['error' => $e->getMessage()]
            ])->start();

            return $e->getMessage();
        }

    }

}
