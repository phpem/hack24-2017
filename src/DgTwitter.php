<?php

namespace App;

use Predis\Client;

class DgTwitter implements TwitterApi
{

    public static $cacheTTL = 345600;

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
        $client::$cacheExpire = '96 hours';

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
        $hashKey = md5($query);

        if ( ! $ret = json_decode($this->redisCache->get($hashKey))) {
            $ret = $this->client->search($query);
            $this->redisCache->set($hashKey, json_encode($ret), 'EX', self::$cacheTTL);
        }

        return $ret;
    }

    public function getFriends(string $username): array
    {
        $request = 'friends/list.json?screen_name=' . $username;
        $hashKey = md5($request);

        if ( ! $ret = json_decode($this->redisCache->get($hashKey))) {
            $ret = $this->client->request($request, 'GET');
            $this->redisCache->set($hashKey, json_encode($ret), 'EX', self::$cacheTTL);
        }

        return $ret->users;
    }

    public function getTweetsForUser(string $username): array
    {
        $request = 'statuses/user_timeline.json?screen_name=' . $username;
        $hashKey = md5($request);

        if ( ! $ret = json_decode($this->redisCache->get($hashKey))) {
            $ret = $this->client->request($request, 'GET');
            $this->redisCache->set($hashKey, json_encode($ret->users), 'EX', self::$cacheTTL);
        }

        return $ret->users;
    }

    public function getUserInfo(string $username): \stdClass
    {

        $request = 'users/show.json?screen_name=' . $username;
        $hashKey = md5($request);

        if ( ! $ret = json_decode($this->redisCache->get($hashKey))) {
            $ret = $this->client->request($request, 'GET');
            $this->redisCache->set($hashKey, json_encode($ret), 'EX', self::$cacheTTL);
        }

        return $ret;
    }
}
