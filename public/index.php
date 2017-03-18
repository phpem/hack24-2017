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
    getenv('TWITTER_TOKEN_SECRET'),
    __DIR__ . '/../cache/'
);

if (isset($_POST['topic'])) {

    $tweets = $twitter->getMeAndFriendsTimeLine();

    $topic = $_POST['topic'];
    $user = $_POST['user'];

    $tweets = $twitter->search(sprintf('%s from:%s', $topic, $user));

    $analysedTweets = [];
    $sentiment = 0;
    $type = 0;


    foreach ($tweets as $tweet) {
        $tweetSentiment = $textapi->Sentiment($tweet->text);

        $analysedTweets[$tweet->id] = [
            'raw_text'          => $tweet->text,
            'analysed_text'     => $tweetSentiment,
            'analysed_entities' => $textapi->Entities($tweet->text)
        ];

        switch ($tweetSentiment->polarity) {
            case 'positive':
                ++$sentiment;
                break;
            case 'negative':
                --$sentiment;
                break;
        }


        switch ($tweetSentiment->subjectivity) {
            case 'objective':
                ++$type;
                break;
            case 'subjective':
                --$type;
                break;
        }
    }

    if ($sentiment === 0) {
        $sentiment = 'neutral';
    }

    if ($sentiment > 0) {
        $sentiment = 'positive';
    }

    if ($sentiment < 0) {
        $sentiment = 'negative';
    }

    if ($type === 0) {
        $type = '';
    }

    if ($type > 0) {
        $type = 'objectively';
    }

    if ($type < 0) {
        $type = 'subjectively';
    }

    echo "Overall, $user is $type $sentiment about $topic";
} else {
    ?>

    <form action="" method="POST">
        <input type="text" name="topic" placeholder="Topic">
        <input type="text" name="user" placeholder="Username">
        <button>Submit</button>
    </form>

    <?php
}
