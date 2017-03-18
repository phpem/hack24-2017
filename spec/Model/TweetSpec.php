<?php

namespace spec\App\Model;

use App\Model\Tweet;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TweetSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Tweet::class);
    }
}
