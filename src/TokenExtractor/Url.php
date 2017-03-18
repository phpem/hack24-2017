<?php

namespace App\TokenExtractor;

use App\TokenExtractor;

class Url implements TokenExtractor
{
    public function extract(string $string): array
    {
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match);

        return $match[0];
    }
}
