<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$textapi = new AYLIEN\TextAPI(getenv("AYLIEN_APP_ID"), getenv("AYLIEN_KEY"));

$input = ['text' => 'Donald Trump is a complete wanker.'];

$sentiment = $textapi->Sentiment($input);

$entities = $textapi->Entities($input);


$twitter = new Twitter(
    getenv("TWITTER_CONSUMER_KEY"),
    getenv("TWITTER_CONSUMER_SECRET"),
    getenv("TWITTER_TOKEN"),
    getenv("TWITTER_TOKEN_SECRET")
);

$tweets = [];

foreach ($twitter->load(Twitter::ME_AND_FRIENDS) as $item) {
    $tweet = new \Model\Tweet();
    $tweet->userName = '';
    $tweet->message = $item->text;

    $tweets[] = $tweet;
}

header("Content-type:application/json");
echo json_encode(['sentiment' => $sentiment, 'entities' => $entities, 'tweets' => $tweets]);

