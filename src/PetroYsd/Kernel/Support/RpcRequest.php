<?php

namespace XuanChen\PetroYsd\Kernel\Support;

class RpcRequest
{

    protected ?string $method;
    protected         $uri;
    protected array   $params = [];

    public function __construct(string $method = null, array $params = [], $uri = '')
    {
        $this->method = $method;
        $this->params = $params;
        $this->uri    = $uri;
    }

    public function setMethod(string $method): RpcRequest
    {
        $this->method = $method;

        return $this;
    }

    public function setUri(string $uri): RpcRequest
    {
        $this->uri = $uri;

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
        ksort($params);
        return http_build_query($params);
    }

    public function getUri()
    {
        return $this->uri;
    }
}
