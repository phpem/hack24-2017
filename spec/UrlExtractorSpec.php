<?php

namespace spec;

use UrlExtractor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UrlExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UrlExtractor::class);
    }

    function it_should_return_an_empty_array_if_no_urls()
    {
        $this->getUrls("")->shouldReturn([]);
    }

    function it_should_return_an_array_containing_a_single_url()
    {
        $this->getUrls(
            "HealthCareNws: Speaker Paul Ryan Ad Uses Donald Trump to Sell Healthcare Bill http://gettopical.com/health-care/21 via @docdhj http://pic.twitter.com/5m9h7KEIyw"
        )->shouldReturn([
            "http://gettopical.com/health-care/21",
            "http://pic.twitter.com/5m9h7KEIyw"
        ]);
    }
}
