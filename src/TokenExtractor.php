<?php

namespace App;

interface TokenExtractor
{
    public function extract(string $string): array;
}
