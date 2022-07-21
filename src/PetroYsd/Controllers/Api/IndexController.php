<?php

namespace XuanChen\PetroYsd\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use XuanChen\PetroYsd;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Api\Controllers\ApiResponse;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class IndexController
{
    use ValidatesRequests, ApiResponse;

    public function grant(Request $request)
    {
        try {

            $inputdata = $request->all();
            $res       = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $res;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'grant'); //添加日志

            if (is_string($res)) {
                return $this->error($res, $this->log);
            }

            $validator = \Validator::make($res, [
                'productNo' => 'required',
                'outletId'  => 'required',
                'mobile'    => 'required',
            ], [
                'productNo.required' => '缺少活动编码',
                'outletId.required'  => '缺少网点id',
                'mobile.required'    => '缺少手机号',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $grant = [
                'requestId'    => $res['thirdOrderId'],
                'productNo'    => $res['productNo'],
                'mobile'       => $res['mobile'],
                'num'          => 1,
                'thirdOrderId' => $res['thirdOrderId'],
            ];

            $res = PetroYsd::Grant()->setParams($grant)->start();

            return $this->success($res, $this->log);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    public function query(Request $request)
    {
        try {
            $inputdata = $request->all();
            $jiemi     = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $jiemi;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'query'); //添加日志

            if (is_string($jiemi)) {
                return $this->error($jiemi, $this->log);
            }

            $validator = \Validator::make($jiemi, [
                'conponCode' => 'required',
            ], [
                'conponCode.required' => '电子券编号',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $coupon = PetroYsdCoupon::where('couponCode', $jiemi['conponCode'])->first();

            if (! $coupon) {
                return $this->error('未找到优惠券信息', $this->log);
            }

            $res = PetroYsd::Detail()
                ->setParams([
                    'requestId'  => Str::random(32),
                    'couponId'   => $coupon->couponId,
                    'couponType' => 0,
                ])->start();

            return $this->success($res, $this->log);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $inputdata = $request->all();
            $jiemi     = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $jiemi;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'destroy'); //添加日志

            if (is_string($jiemi)) {
                return $this->error($jiemi, $this->log);
            }

            $validator = \Validator::make($jiemi, [
                'conponCode' => 'required',
            ], [
                'conponCode.required' => '缺少券码',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $coupon = PetroYsdCoupon::where('couponCode', $jiemi['conponCode'])->first();

            if (! $coupon) {
                return $this->error('未找到优惠券信息', $this->log);
            }

            $res = PetroYsd::Invalid()
                ->setParams([
                    'requestId'  => Str::random(32),
                    'couponId'   => $coupon->couponId,
                    'couponType' => 0,
                ])->start();

            return $this->success($res, $this->log);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    /**
     * Notes: description
     *
     * @Author: 玄尘
     * @Date: 2022/3/2 11:21
     */
    public function notice(Request $request)
    {
        try {
            $data = $request->all();

            $validator = \Validator::make($data, [
                'couponId'     => 'required',
                'timestamp'    => 'required',
                'useTime'      => 'required',
                'useShop'      => 'required',
                'thirdOrderId' => 'required',
                'state'        => 'required',
                'sign'         => 'required',
            ], [
                'couponId.required'     => '缺少卡券ID',
                'timestamp.required'    => '缺少时间戳',
                'useTime.required'      => '缺少使用时间',
                'useShop.required'      => '缺少使用门店',
                'thirdOrderId.required' => '缺少三方订单号',
                'state.required'        => '缺少卡券状态',
                'sign.required'         => '缺少签名',
            ]);


            $this->log = $this->createLog($request->url(), 'POST', $data, 'notice'); //添加日志
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $res = PetroYsd::Notice()->setParams($data, 'in')->start();

            $this->updateLog($this->log, $res); //更新日志
            return response()->json($res);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    /**
     * Notes: 发券结果通知接口
     *
     * @Author: 玄尘
     * @Date: 2022/5/25 10:17
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grantNotice(Request $request)
    {
        try {
            $data = $request->all();

            $validator = \Validator::make($data, [
                'requestId'    => 'required',
                'timestamp'    => 'required',
                'thirdOrderId' => 'required',
                'data'         => 'required',
                'sign'         => 'required',
            ], [
                'requestId.required'    => '缺少卡券ID',
                'timestamp.required'    => '缺少时间戳',
                'thirdOrderId.required' => '缺少三方订单号',
                'data.required'         => '缺少券信息',
                'sign.required'         => '缺少签名',
            ]);

            $this->log = $this->createLog($request->url(), 'POST', $data, 'query'); //添加日志

            $res = PetroYsd::GrantNotice()->setParams($data, 'in')->start();

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

}
