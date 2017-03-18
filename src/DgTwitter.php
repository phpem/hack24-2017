<?php

namespace App;

class DgTwitter implements TwitterApi
{
    private $client;

    public static function create($consumerKey, $consumerSecret, $token, $tokenSecret) {
        $client = new \Twitter(
            $consumerKey,
            $consumerSecret,
            $token,
            $tokenSecret
        );

        $created = new DgTwitter();
        $created->client = $client;

        return $created;
    }

    public function getMeAndFriendsTimeLine()
    {
        $tweets = [];

        foreach ($this->client->load(\Twitter::ME_AND_FRIENDS) as $item) {
            $tweet = new Model\Tweet();
            $tweet->userName = $item->user->screen_name;
            $tweet->message = $item->text;
            foreach($item->entities->hashtags as $hashtag) {
                $tweet->hashTags[] = $hashtag->text;
            }
            foreach ($item->entities->urls as $url) {
                $tweet->urls[] = $url->expanded_url;
            }

            $tweets[] = $tweet;
        }

        return $tweets;
    }
}
