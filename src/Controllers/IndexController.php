<?php

namespace App\Controllers;

use App\DgTwitter;
use AYLIEN\TextAPI;
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
     * @var TextAPI
     */
    private $textAPI;

    public function __construct(Twig $view, DgTwitter $twitterAPI, TextAPI $textAPI)
    {
        $this->view = $view;
        $this->twitterAPI = $twitterAPI;
        $this->textAPI = $textAPI;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->view->render($response, 'index/index.html.twig');
    }

    public function topic(ServerRequestInterface $request, ResponseInterface $response, array  $args)
    {
        $tweets = $this->twitterAPI->getMeAndFriendsTimeLine();

        $topic = $request->getParsedBody()['topic'];
        $user = $request->getParsedBody()['user'];

        $tweets = $this->twitterAPI->search(sprintf('%s from:%s', $topic, $user));

        $analysedTweets = [];
        $sentiment = 0;
        $type = 0;


        foreach ($tweets as $tweet) {
            $tweetSentiment = $this->textAPI->Sentiment($tweet->text);

            $analysedTweets[$tweet->id] = [
                'raw_text'          => $tweet->text,
                'analysed_text'     => $tweetSentiment,
                'analysed_entities' => $this->textAPI->Entities($tweet->text)
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

        return $this->view->render($response, 'index/topic.html.twig', [
            'user' => $user,
            'type' => $type,
            'sentiment' => $sentiment,
            'topic' => $topic
        ]);
    }

}