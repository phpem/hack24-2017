<?php

namespace App;

use AYLIEN\TextAPI;
use Predis\Client;

class CachedAYLIEN
{
    static $cacheTTL = 345600;

    private $redisCache;

    private $textAPI;

    public function __construct(Client $redisCache, TextAPI $textAPI)
    {
        $this->redisCache = $redisCache;
        $this->textAPI = $textAPI;
    }

    public function __call(string $method, array $arguments)
    {
        $hash_key = md5($method . json_encode($arguments));

        if ( !$ret = json_decode($this->get($hash_key))) {
            $ret = call_user_func_array(array($this->textAPI, $method), $arguments);
            $this->put($hash_key, json_encode($ret));
        }

        return $ret;
    }

    public function put(string $name, string $item)
    {
        $this->redisCache->set($name, $item, 'EX', self::$cacheTTL);
    }

    public function get(string $name)
    {
        return $this->redisCache->get($name);
    }
}
