<?php

namespace XuanChen\PetroYsd\Kernel;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;

class BaseClient
{
    protected $app;

    protected $config;

    //传入参数
    protected $params;

    protected $body;

    public $verifyCode;//签名

    protected $client;

    public function __construct($app)
    {
        $this->app    = $app;
        $this->config = $app->config;
        $this->client = $app->client;
        $this->rsa    = $app->rsa;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    public function getMsecTime()
    {
        return Carbon::now()->timestamp;
    }

    /**
     * Notes: 签名
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 9:52
     */
    public function getSign()
    {
        $signString = $this->getSignString();

        return $this->app->rsa->sign($signString);
    }

    /**
     * Notes: 获取签名字符串
     *
     * @Author: 玄尘
     * @Date: 2022/5/23 10:57
     * @return string
     */
    public function getSignString()
    {
        $params = $this->params;
        ksort($params);
        return join("", array_values($params));
    }


    /**
     * Notes: 设置传入数据
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 9:28
     * @param  array  $args
     * @return $this
     */
    public function setParams(array $args, $type = 'out'): self
    {
        if ($type == 'out') {
            $this->params = array_merge($args, [
                'channelCode' => $this->config['channelCode'],
                'timestamp'   => $this->getMsecTime(),
            ]);
        } else {
            $this->params = $args;
        }

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
        return $this->params;
    }

    /**
     * Notes: 插入日志
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 15:11
     */
    public function addLog($msg = '')
    {
        $out_source = ['error' => $msg];
        if ($this->client->resData) {
            $out_source = $this->client->resData;
        }

        $this->app->log->setData([
            'type'       => $this->type,
            'in_source'  => $this->params,
            'out_source' => $out_source
        ])->start();

    }

}
