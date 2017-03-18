<?php

namespace spec\App\TokenExtractor;

use App\TokenExtractor\HashTag;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HashTagSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HashTag::class);
    }

    function it_should_return_an_empty_array_for_no_hashtags()
    {
        $this->extract(
            'HuffPostUK: 5 things Donald Trump tried to bury this week huff.to/2mGrmeC pic.twitter.com/jwUWV26E1l'
        )->shouldReturn([]);
    }

    function it_should_return_array_of_hashtags()
    {
        $this->extract(
            'ActivistWire: Who are the wealthy Russians investing in Trump luxury buildings? #DonaldTrump #GilDezers reuters.com/investigates/s…'
        )->shouldReturn(['#DonaldTrump', '#GilDezers']);
    }
}
