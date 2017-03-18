<?php

use App\DgTwitter;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$textapi = new AYLIEN\TextAPI(getenv("AYLIEN_APP_ID"), getenv("AYLIEN_KEY"));

$tweets = [];

$twitter = App\DgTwitter::create(
    getenv('TWITTER_CONSUMER_KEY'),
    getenv('TWITTER_CONSUMER_SECRET'),
    getenv('TWITTER_TOKEN'),
    getenv('TWITTER_TOKEN_SECRET')
);

if (isset($_POST['topic'])) {
    header('Content-type:application/json');

    $tweets = $twitter->getMeAndFriendsTimeLine();

    $tweets = $twitter->search(sprintf('%s from:brunty', $_POST['topic']));

    $analysedTweets = [];

    foreach ($tweets as $tweet) {
        $analysedTweets[$tweet->id] = [
            'raw_text'          => $tweet->text,
            'analysed_text'     => $textapi->Sentiment($tweet->text),
            'analysed_entities' => $textapi->Entities($tweet->text)

        ];
    }
    echo json_encode(['tweets' => $analysedTweets]);
} else {
    ?>

    <form action="" method="POST">
        <input type="text" name="topic">
        <button>Submit</button>
    </form>

    <?php
}
