<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$textapi = new AYLIEN\TextAPI(getenv("AYLIEN_APP_ID"), getenv("AYLIEN_KEY"));

$input = ['text' => 'Donald Trump is a complete wanker.'];

$sentiment = $textapi->Sentiment($input);

$entities = $textapi->Entities($input);

var_dump($sentiment);
var_dump($entities);