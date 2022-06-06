<?php

namespace XuanChen\PetroYsd\Grant;

use Exception;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class Client extends BaseClient
{
    protected $path = '/channel/order/v1';
    protected $type = 'grant';
    protected $mobile;

    public function __construct($app)
    {
        parent::__construct($app);
    }

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
            $this->mobile = $this->params['mobile'];

            $this->params['mobile'] = $this->app->rsa->encodeByPublicKey($this->params['mobile']);
            $this->params['sign']   = $this->getSign();


            $this->client->getGrant($this->getPostData(), $this->path);//获取优惠券

            $this->addLog();//插入日志

            $ticketDetail = $this->client->resData['data']['0'];
            $couponCode   = $this->app->rsa->decodeByPrivateKey($ticketDetail['couponCode']);

            $coupon = PetroYsdCoupon::query()->where('couponCode', $couponCode)->first();
            if ($coupon) {
                return $coupon;
            }

            return PetroYsdCoupon::create([
                'petro_log_id'    => $this->app->log->source->id,
                'mobile'          => $this->mobile,
                'productName'     => $ticketDetail['productName'],
                'productId'       => $ticketDetail['productId'],
                'thirdOrderId'    => $this->params['thirdOrderId'],
                'couponId'        => $ticketDetail['id'],
                'couponCode'      => $couponCode,
                'cashAmount'      => $ticketDetail['cashAmount'] ?? 0,
                'faceValue'       => $ticketDetail['faceValue'],
                'couponBeginDate' => $ticketDetail['couponBeginDate'],
                'couponEndDate'   => $ticketDetail['couponEndDate'],
                'issuingDate'     => $ticketDetail['issuingDate'],
                'productType'     => $ticketDetail['productType'],
            ]);

        } catch (\Exception $e) {
            $this->addLog($e->getMessage());
            return $e->getMessage();
        }

    }

}
