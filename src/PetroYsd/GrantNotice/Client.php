<?php

namespace XuanChen\PetroYsd\GrantNotice;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\Event\CouponNotice;

class Client extends BaseClient
{

    public $res;

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

            $this->res = [
                'requestId' => Str::random(32),
                'code'      => 200,
                'msg'       => '成功',
                'data'      => '',
            ];

            $this->app->rsa->checkSign($this->params);

            $this->addLog();//插入日志

            $ticketDetail = $this->params['data']['0'];
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


            return $this->res;
        } catch (\Exception $e) {
            $this->res['code'] = 499;
            $this->res['msg']  = $e->getMessage();

            return $this->res;
        }

    }

}
