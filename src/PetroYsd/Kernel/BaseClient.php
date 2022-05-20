<?php

namespace XuanChen\PetroYsd\Kernel;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BaseClient
{
    protected $app;

    protected $config;

    protected $method = 'AES-128-ECB';

    protected $params;//传入参数

    protected $body;//aes params

    public $verifyCode;//签名

    protected $client;

    public $mobile;

    protected $strActionType;

    public function __construct($app)
    {
        $this->app    = $app;
        $this->config = $app->config;
        $this->client = $app->client;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    public function getMsecTime()
    {
        [$msec, $sec] = explode(' ', microtime());
        $msectime = (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $msectime = explode('.', $msectime);

        return $msectime[0];
    }

    public function setActionType($type)
    {
        $this->strActionType = $type;
        return $this;
    }


    /**
     * Notes: 签名
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 9:52
     */
    public function setSign(string $body)
    {
        $this->str        = $body.$this->config['merchantKey'];
        $this->verifyCode = md5($this->str);
        return $this;
    }

    /**
     * Notes: 设置header
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 10:05
     */
    public function getHeader()
    {
        if (! $this->verifyCode) {
            $this->setSign($this->encrypt($this->params));
        }

        $this->header = [
            'characterSet'  => Arr::get($this->config, 'characterSet', ''),
            'strVendorCode' => Arr::get($this->config, 'strVendorCode', ''),
            'signType'      => Arr::get($this->config, 'signType', 'MD5'),
            'strActionType' => $this->strActionType,
            'verifyCode'    => $this->verifyCode,
            'timestamp'     => $this->getMsecTime(),
        ];

        return $this->header;
    }

    //加密
    public function encrypt($data): string
    {
        if (! is_string($data) && ! is_array($data)) {
            throw new \Exception('The encrypt data must be a string or an array.');
        }

        if (is_array($data)) {
            $data = json_encode($data);
        }

        return $this->body = base64_encode(openssl_encrypt($data, $this->method, $this->config['merchantKey'],
            OPENSSL_RAW_DATA));
    }

    //解密
    public function decrypt($data, $merchanKey = '')
    {
        if (! $merchanKey) {
            $merchanKey = $this->config['merchantKey'];
        }

        $data  = openssl_decrypt(base64_decode($data), $this->method, $merchanKey, OPENSSL_RAW_DATA);
        $array = json_decode($data, true);
        return is_array($array) ? $array : $data;
    }

    /**
     * Notes: 设置传入数据
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 9:28
     * @param  array  $args
     * @return $this
     */
    public function setParams(array $args): self
    {
        $this->params = $args;
        return $this;
    }

    /**
     * Notes: 设置手机号
     *
     * @Author: 玄尘
     * @Date: 2022/2/23 14:10
     * @param  string  $mobile
     * @return $this
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * Notes: 获取请求数据
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 14:07
     * @return array[]
     */
    public function getPostData(): array
    {
        return [
            'sendMessage' => [
                'head' => $this->getHeader(),
                'body' => $this->body,
            ]
        ];
    }

    /**
     * Notes: 返回中石油的数据
     *
     * @Author: 玄尘
     * @Date: 2022/3/2 16:51
     */
    public function getBackData($status, $list)
    {
        $list = trim($list, ',');

        $params = [
            "couponStateChangeReponseVo" => [
                "msg"    => $status ? "成功" : "失败",
                "okList" => $list,
                "status" => $status ? 1 : -1
            ]
        ];

        $this->setSign($this->encrypt($params));
        return [
            'postMessage' => [
                'head' => [
                    "requestType"  => "ELEXX002",
                    "message"      => $status ? "成功" : "失败",
                    "user"         => "PETROCHINA",
                    "uuid"         => Str::uuid()->toString(),
                    "responseCode" => 0,
                    "verifyCode"   => $this->verifyCode,
                    "timestamp"    => Carbon::now()->format('Y-m-d')
                ],
                'body' => $this->encrypt($params),
            ]
        ];
    }
}
