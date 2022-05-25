<?php

namespace XuanChen\PetroYsd\Log;

use Exception;
use Illuminate\Support\Arr;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdLog;

class Client
{
    protected $params;

    public $source;

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
            $this->source = PetroYsdLog::create([
                'type'       => Arr::get($this->params, 'type', ''),
                'in_source'  => Arr::get($this->params, 'in_source', ''),
                'out_source' => Arr::get($this->params, 'out_source', ''),
            ]);

            return $this;
        } catch (PetroYsdException $exception) {
            throw new PetroYsdException($exception->getMessage());
        }

    }

    /**
     * Notes: 设置入库数据
     *
     * @Author: 玄尘
     * @Date: 2022/2/22 14:58
     * @param $params
     * @return mixed
     */
    public function setData($params)
    {
        $this->params = $params;

        return $this;
    }

}
