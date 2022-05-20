<?php

namespace XuanChen\PetroYsd\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use XuanChen\PetroYsd;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Api\Controllers\ApiResponse;

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
                'activityId' => 'required',
                'outletId'   => 'required',
                'mobile'     => 'required',
            ], [
                'activityId.required' => '缺少活动编码',
                'outletId.required'   => '缺少网点id',
                'mobile.required'     => '缺少手机号',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $grant = [
                'requestCode' => $res['activityId'],
                'tradeId'     => $res['tradeId'],
                'ticketSum'   => 1,
                'amount'      => $res['amount'],
                'random'      => Str::random(6),
            ];

            $res = PetroYsd::Grant()->setParams($grant)->setMobile($res['mobile'])->start();
            return $this->success($res, $this->log);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    public function query(Request $request)
    {
        try {
            $inputdata = $request->all();
            $res       = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $res;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'query'); //添加日志

            if (is_string($res)) {
                return $this->error($res, $this->log);
            }

            $validator = \Validator::make($res, [
                'couponNo' => 'required',
            ], [
                'couponNo.required' => '缺少券码',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $res = PetroYsd::Detail()->setParams([
                'couponNo' => $res['couponNo'],
                'random'   => Str::random(6),
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
            $res       = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $res;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'query'); //添加日志

            if (is_string($res)) {
                return $this->error($res, $this->log);
            }

            $validator = \Validator::make($res, [
                'couponNo' => 'required',
            ], [
                'couponNo.required' => '缺少券码',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $res = PetroYsd::Invalid()->setParams([
                'cxcouponNo' => $res['couponNo'],
                'random'     => Str::random(6),
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
            $data      = $request->all();
            $validator = \Validator::make($data, [
                'sendMessage' => 'required',
            ], [
                'sendMessage.required' => '缺少参数',
            ]);


            $this->log = $this->createLog($request->url(), 'POST', $data, 'query'); //添加日志
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $res = PetroYsd::Notice()->setParams($data)->start();

            $this->updateLog($this->log, $res); //更新日志
            return response()->json($res);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }

    /**
     * Notes: 获取动态码
     *
     * @Author: 玄尘
     * @Date: 2022/3/4 13:31
     * @param  Request  $request
     */
    public function checkCode(Request $request)
    {
        try {
            $inputdata = $request->all();
            $res       = $this->checkSign($request);

            //获取解密后数据
            $inputdata['jiemi'] = $res;
            $this->log          = $this->createLog($request->url(), 'POST', $inputdata, 'query'); //添加日志

            if (is_string($res)) {
                return $this->error($res, $this->log);
            }

            $validator = \Validator::make($res, [
                'couponNo' => 'required',
            ], [
                'couponNo.required' => '缺少券码',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), $this->log);
            }

            $code = PetroYsd::Check()->setParams([
                'ticketNum' => $res['couponNo'],
                'random'    => Str::random(6),
            ])->start();

            return $this->success($code, $this->log);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $this->log);
        }
    }


}
