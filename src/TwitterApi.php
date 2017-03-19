<?php

namespace App;

interface TwitterApi
{
    public function getMeAndFriendsTimeLine(): array;

    public function getFriends(string $username): array;

    public function search(string $query): array;

    public function getTweetsForUser(string $username): array;
}
