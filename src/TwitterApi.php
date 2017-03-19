<?php

namespace App;

interface TwitterApi
{
    public function getMeAndFriendsTimeLine(): array;

    public function getFriends($username): array;

    public function search(string $query): array;
}
