<?php


require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$textapi = new AYLIEN\TextAPI(getenv("AYLIEN_APP_ID"), getenv("AYLIEN_KEY"));
$sentiment = $textapi->Sentiment(
    [
        'text' => 'Donald Trump is a complete wanker.'
    ]
);

$entities = $textapi->Entities(
    [
        'text' => 'Donald Trump is a complete wanker.'
    ]
);


var_dump($sentiment);
var_dump($entities);
