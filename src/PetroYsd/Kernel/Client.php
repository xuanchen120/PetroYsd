<?php

namespace XuanChen\PetroYsd\Kernel;

use Exception;
use GuzzleHttp\Client as Guzzle;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\Support\RpcRequest;

class Client
{
    protected $app;

    protected $config;

    public $client;

    public $postData;

    public $resData;

    public $response;

    public function __construct($app)
    {
        $this->app    = $app;
        $this->config = $app->config;
        $this->client = new Guzzle([
            'base_uri' => $this->config['base_uri'],
        ]);

    }

    public function __call($method, $args)
    {
        return $this->request($method, ...$args);
    }

    public function request(string $method, array $params = [], $uri = '')
    {
        $rpcRequest = new RpcRequest();

        $rpcRequest->setMethod($method);
        $rpcRequest->setUri($uri);

        if (! empty($params)) {
            $rpcRequest->setParams($params);
        }

        return $this->post($rpcRequest);
    }

    protected function post(RpcRequest $body)
    {
        try {
            $this->postData = $body->getParams();

            $this->response = $this->client->post($body->getUri(), [
                'form_params' => $this->postData,
            ]);

            $this->resData = json_decode($this->response->getBody()->getContents(), true);

            if (is_null($this->resData)) {
                throw new PetroYsdException('未获取到返回数据');
            }

            if ($this->resData['code'] != 200) {
                throw new PetroYsdException($this->resData['msg']);
            }

            return true;
        } catch (Exception $exception) {
            throw new PetroYsdException($exception->getMessage());
        }
    }
}
