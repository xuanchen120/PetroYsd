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


            $coupon = PetroYsdCoupon::query()
                ->where('couponId', $this->params['couponId'])
                ->first();

            if (! $coupon) {
                throw  new PetroYsdException('未找到优惠券信息');
            }

            $coupon->update([
                'useTime' => $this->params['useTime'],
                'useShop' => $this->params['useShop'],
                'state'   => $this->params['state'],
            ]);

            return $this->res;
        } catch (\Exception $e) {
            $this->res['code'] = 499;
            $this->res['msg']  = $e->getMessage();

            return $this->res;
        }

    }

}
