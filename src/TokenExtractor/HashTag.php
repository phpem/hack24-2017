<?php

namespace App\TokenExtractor;

use App\TokenExtractor;

class HashTag implements TokenExtractor
{
    public function extract(string $string): array
    {
        preg_match_all("/(#\w+)/", $string, $matches);

        return $matches[0];
    }
}
