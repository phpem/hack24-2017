<?php

namespace App;

use AYLIEN\TextAPI;
use Predis\Client;

class CachedAYLIEN
{
    public static $cacheTTL = 345600;

    private $redisCache;

    private $textAPI;

    public function __construct(Client $redisCache, TextAPI $textAPI)
    {
        $this->redisCache = $redisCache;
        $this->textAPI = $textAPI;
    }

    public function __call(string $method, array $arguments)
    {
        $hashKey = md5($method . json_encode($arguments));

        if ( !$ret = json_decode($this->get($hashKey))) {
            $ret = call_user_func_array(array($this->textAPI, $method), $arguments);
            $this->put($hashKey, json_encode($ret));
        }

        return $ret;
    }

    private function put(string $name, string $item)
    {
        $this->redisCache->set($name, $item, 'EX', self::$cacheTTL);
    }

    private function get(string $name)
    {
        return $this->redisCache->get($name);
    }
}
