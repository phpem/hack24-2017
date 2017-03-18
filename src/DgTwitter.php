<?php

namespace App;

class DgTwitter implements TwitterApi
{
    /** @var  \Twitter */
    private $client;

    public static function create($consumerKey, $consumerSecret, $token, $tokenSecret, $cacheDir)
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
        return $this->client->search($query);
    }
}
