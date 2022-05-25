<?php

namespace XuanChen\PetroYsd\Rsa;

use Exception;
use GuzzleHttp\Client as Guzzle;
use XuanChen\PetroYsd\Exceptions\PetroYsdException;
use XuanChen\PetroYsd\Kernel\BaseClient;

class Client extends BaseClient
{
    public function __construct($app)
    {
        $this->app    = $app;
        $this->config = $app->config;
        $this->checkDefaultData();
    }

    /**
     * Notes: 检查基础数据
     *
     * @Author: 玄尘
     * @Date  : 2021/4/30 10:56
     */
    public function checkDefaultData()
    {
        if (empty($this->config['private_key'])) {
            throw new PetroYsdException('缺少私钥');
        }

        if (empty($this->config['public_key'])) {
            throw new PetroYsdException('缺少公钥');
        }
    }


    /**
     * Notes: 获取私钥
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 10:14
     * @return false|resource
     */
    public function getPrivateKey()
    {
        $pem = "-----BEGIN PRIVATE KEY-----".PHP_EOL;
        $pem .= chunk_split($this->config['private_key'], 64, PHP_EOL);
        $pem .= "-----END PRIVATE KEY-----".PHP_EOL;
        return openssl_pkey_get_private($pem);
    }

    /**
     * Notes: 获取公钥
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 10:14
     * @return false|resource
     */
    public function getPublicKey()
    {
        $pem = "-----BEGIN PUBLIC KEY-----".PHP_EOL;
        $pem .= chunk_split($this->config['public_key'], 64, PHP_EOL);
        $pem .= "-----END PUBLIC KEY-----".PHP_EOL;
        return openssl_pkey_get_public($pem);
    }

    /**
     * 私钥加密
     *
     * @param  string  $data  要加密的数据
     * @return mixed
     */
    public function encodeByPrivateKey($data)
    {
        $crypto = '';
        $length = $this->_getKeyLength() / 8 - 11;

        foreach (str_split($data, $length) as $chunk) {
            openssl_private_encrypt($chunk, $encrypted, $this->getPrivateKey());
            $crypto .= $encrypted;
        }

        return $this->_base64Encode($crypto);
    }

    /**
     * Notes: 公钥加密
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 9:54
     * @param $data
     * @return mixed
     */
    public function encodeByPublicKey($data)
    {
        $crypto = '';
        $length = $this->_getKeyLength() / 8 - 11;
        foreach (str_split($data, $length) as $chunk) {
            openssl_public_encrypt($chunk, $encrypted, $this->getPublicKey());
            $crypto .= $encrypted;
        }
        return $this->_base64Encode($crypto);
    }

    /**
     * Notes: 公钥解密
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 10:15
     * @param $data
     * @return string
     */
    public function decodeByPublicKey($data)
    {
        $crypto = '';
        $length = $this->_getKeyLength() / 8;

        foreach (str_split($this->_base64Decode($data), $length) as $chunk) {
            openssl_public_decrypt($chunk, $decrypted, $this->getPublicKey());
            $crypto .= $decrypted;
        }

        return $crypto;
    }

    /**
     * Notes: 私钥解密
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 10:15
     * @param $data
     * @return string
     */
    public function decodeByPrivateKey($data)
    {
        $crypto = '';
        $length = $this->_getKeyLength() / 8;

        foreach (str_split($this->_base64Decode($data), $length) as $chunk) {
            openssl_private_decrypt($chunk, $decrypted, $this->getPrivateKey());
            $crypto .= $decrypted;
        }

        return $crypto;
    }

    /**
     * 获取密钥长度
     *
     * @return mixed
     */
    private function _getKeyLength()
    {
        return openssl_pkey_get_details($this->getPublicKey())['bits'];
    }

    /**
     * @param  string  $value  待加密字符串
     * @return mixed
     */
    private function _base64Encode($value)
    {
        $data = base64_encode($value);
        return $data;
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }

    /**
     * @param  string  $value  待解密字符串
     * @return bool|string
     */
    private function _base64Decode($value)
    {
        return base64_decode($value);

        $data = str_replace(['-', '_'], ['+', '/'], $value);

        if ($mod4 = strlen($data) % 4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    /**
     * Notes: 签名
     *
     * @Author: 玄尘
     * @Date: 2022/5/24 10:26
     * @param $str
     */
    public function sign($signString)
    {
        openssl_sign($signString, $sign, $this->getPrivateKey(), OPENSSL_ALGO_MD5);
        return base64_encode($sign);
    }

    /**
     * Notes: 验签
     *
     * @Author: 玄尘
     * @Date: 2022/5/25 11:12
     * @param $out
     * @param $self
     * @return bool
     * @throws Exception
     */
    public function checkSign(array $data)
    {
        $sign = $data['sign'];
        $sign = str_replace('\\', '', $sign);
        $sign = str_replace(' ', '+', $sign);
        $sign = str_replace(PHP_EOL, '', $sign);
        $sign = base64_decode($sign);

        unset($data['sign']);

        ksort($data);

        $signStr = join("", array_values($data));

        $result = (bool) openssl_verify($signStr, $sign, $this->getPublicKey(), OPENSSL_ALGO_MD5);
        if (! $result) {
            throw new \Exception('验签失败');
        }

        return $result;
    }

    /**
     * Notes: 校验sign
     *
     * @Author: 玄尘
     * @Date  : 2020/10/13 15:21
     * @param         $data
     * @param  false  $types
     * @return int|string
     */
    public function hexXbin($sign, $types = false)
    {
        // 过滤非16进制字符
        $checkStr = strspn($sign, '0123456789abcdefABCDEF');
        //字符串长度不是偶数时pack来处理
        if (strlen($checkStr) % 2) {
            return pack("H*", $sign);
        } else {
            return hex2bin($sign);
        }
    }

}
