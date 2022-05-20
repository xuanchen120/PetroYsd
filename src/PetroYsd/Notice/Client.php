<?php

namespace XuanChen\PetroYsd\Notice;

use Carbon\Carbon;
use Exception;
use XuanChen\PetroYsd\Kernel\BaseClient;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\Event\CouponNotice;

class Client extends BaseClient
{

    public $coupon_list;

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
            $this->setActionType('Notice');
            $verifyCode = $this->params['sendMessage']['head']['verifyCode'];
            $body       = $this->params['sendMessage']['body'];
            $this->setSign($body);

            if ($verifyCode != strtoupper($this->verifyCode)) {
                throw  new  PetroYsdException('签名错误');
            }

            $data = $this->decrypt($body);

            $couponList = $data['couponStateChangeRequestVo']['couponList'];

            foreach ($couponList as $couponInfo) {
                $coupon = PetroYsdCoupon::query()->where('couponNo', $couponInfo['couponNo'])->first();
                if ($coupon) {
                    $coupon->update([
                        'useTime'     => Carbon::parse($couponInfo['useTime']),
                        'status'      => $couponInfo['status'],
                        'stationName' => $couponInfo['stationName'],
                        'stationCode' => $couponInfo['stationCode'],
                        'goodsInfo'   => $couponInfo['goodsInfo'],
                    ]);
                }

                $this->coupon_list .= ','.$couponInfo['couponNo'];
                event(new CouponNotice($coupon));//通知
            }

            $backData = $this->getBackData(true, $this->coupon_list);

            $this->app->log->setData([
                'in_source'  => $this->params,
                'out_source' => $backData
            ])->start();

            return $backData;

        } catch (\Exception $e) {
            $this->app->log->setData([
                'in_source'  => $this->params,
                'out_source' => $this->getBackData(false, $this->coupon_list)
            ])->start();
            return $e->getMessage();
        }

    }

}
