<?php

namespace XuanChen\PetroYsd\Kernel\Support;

class RpcRequest
{

    protected ?string $method;

    protected array $params = [];

    public function __construct(string $method = null, array $params = [])
    {
        $this->method = $method;
        $this->params = $params;
    }

    public function setMethod(string $method): RpcRequest
    {
        $this->method = $method;

        return $this;
    }

    public function setParams($params = []): RpcRequest
    {
        $this->params = $params;
        return $this;
    }

    public function toJson(): string
    {
        return json_encode($this->params);
    }

    public function __toString(): string
    {
        $data = [
            'method' => $this->method,
            'params' => $this->params,
        ];

        return json_encode($data);
    }

    public function getParams()
    {
        return $this->params;
    }
}
