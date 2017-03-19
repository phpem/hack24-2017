<?php

namespace App\Controllers;

use App\DgTwitter;
use App\CachedAYLIEN;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class IndexController
{

    protected $view;

    /**
     * @var DgTwitter
     */
    private $twitterAPI;

    /**
     * @var CachedAYLIEN
     */
    private $textAPI;

    public function __construct(Twig $view, DgTwitter $twitterAPI, CachedAYLIEN $textAPI)
    {
        $this->view = $view;
        $this->twitterAPI = $twitterAPI;
        $this->textAPI = $textAPI;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view->render($response, 'index/index.html.twig');
    }

    public function topic(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $topic = $request->getQueryParams()['topic'];
        $user = $request->getQueryParams()['username'];

        $userInfo = $this->twitterAPI->getUserInfo($user);

        $tweets = $this->twitterAPI->search(sprintf('%s from:%s', $topic, $user));

        $userSentiment = 0;
        $first = true;

        foreach ($tweets as $tweet) {
            $tweetSentiment = $this->textAPI->Sentiment($tweet->text);

            switch ($tweetSentiment->polarity) {
                case 'positive':
                    $userSentiment += 1 * $tweetSentiment->polarity_confidence;
                    break;
                case 'negative':
                    $userSentiment += -1 * $tweetSentiment->polarity_confidence;
                    break;
            }

            if ( ! $first) {
                $userSentiment /= 2;
            }

            $first = false;
        }

        $friends = $this->twitterAPI->getFriends($user);

        $friendSentiments = [];
        $totalTweets = 0;

        foreach ($friends as $friend) {
            $friendSentiment = 0;
            $tweets = $this->twitterAPI->search("$topic from:{$friend->screen_name}");
            $totalTweets += count($tweets);

            $first = true;
            foreach ($tweets as $tweet) {
                $tweetSentiment = $this->textAPI->Sentiment($tweet->text);

                switch ($tweetSentiment->polarity) {
                    case 'positive':
                        $friendSentiment += 1 * $tweetSentiment->polarity_confidence;
                        break;
                    case 'negative':
                        $friendSentiment += -1 * $tweetSentiment->polarity_confidence;
                        break;
                }

                if ( ! $first) {
                    $friendSentiment /= 2;
                }

                $first = false;
            }

            $friendSentiments[$friend->screen_name] = $friendSentiment;
        }

        $averageFriendSentiment = array_sum($friendSentiments) / count($friendSentiments);

        $echoChamber = false;
        $positiveThreshold = 0.4;
        $negativeThreshold = -0.4;
        $userAndFriendSentimentIsPositive = $userSentiment > $positiveThreshold && $averageFriendSentiment > $positiveThreshold;
        $userAndFriendSentimentIsNegative = $userSentiment < $negativeThreshold && $averageFriendSentiment < $negativeThreshold;

        if ($userAndFriendSentimentIsPositive || $userAndFriendSentimentIsNegative) {
            $echoChamber = true;
        }

        return $this->view->render(
            $response,
            'index/topic.html.twig',
            [
                'user'         => $user,
                'user_info'    => $userInfo,
                'echo_chamber' => $echoChamber,
                'friends'      => $friends,
                'sentiment'    => $userSentiment,
                'topic'        => $topic
            ]
        );
    }
}
