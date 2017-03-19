<?php

namespace App;

use Predis\Client;

class DgTwitter implements TwitterApi
{
    static $cacheTTL = 345600;

    /** @var  \Twitter */
    private $client;

    /**
     * @var Client
     */
    public $redisCache;

    public static function create($consumerKey, $consumerSecret, $token, $tokenSecret, $cacheDir, $cacheRedis)
    {
        $client = new \Twitter(
            $consumerKey,
            $consumerSecret,
            $token,
            $tokenSecret
        );

        $client->httpOptions[CURLOPT_SSL_VERIFYPEER] = true;

        $client::$cacheDir = $cacheDir;
        $client::$cacheExpire = "96 hours";

        $created = new DgTwitter();
        $created->client = $client;

        $created->redisCache = $cacheRedis;

        return $created;
    }

    public function getMeAndFriendsTimeLine(): array
    {
        $tweets = [];

        foreach ($this->client->load(\Twitter::ME_AND_FRIENDS, 200) as $item) {
            $tweet = new Model\Tweet();
            $tweet->userName = $item->user->screen_name;
            $tweet->message = $item->text;
            foreach ($item->entities->hashtags as $hashtag) {
                $tweet->hashTags[] = $hashtag->text;
            }
            foreach ($item->entities->urls as $url) {
                $tweet->urls[] = $url->expanded_url;
            }

            $tweets[] = $tweet;
        }

        return $tweets;
    }

    public function search(string $query): array
    {
        $hash_key = md5($query);

        if ( !$ret = json_decode($this->redisCache->get($hash_key))) {
            $ret = $this->client->search($query);
            $this->redisCache->set($hash_key, json_encode($ret), 'EX', self::$cacheTTL);
        }

        return $ret;
    }

    public function getFriends($username): array
    {
        $hashKey = md5($username);

        if ( ! $ret = json_decode($this->redisCache->get($hashKey))) {
            $ret = $this->client->request('friends/list', 'GET');
            $this->redisCache->set($hashKey, json_encode($ret), 'EX', self::$cacheTTL);
        }

        return $ret;
    }
}
