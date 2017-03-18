<?php

class UrlExtractor
{
    public function getUrls($string)
    {
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match);

        return $match[0];
    }
}
