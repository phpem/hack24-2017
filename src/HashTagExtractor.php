<?php

class HashTagExtractor
{
    public function getHashTags($string)
    {
        preg_match_all("/(#\w+)/", $string, $matches);

        return $matches[0];
    }
}
