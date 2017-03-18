<?php

namespace App\Model;

class Tweet implements \JsonSerializable
{
    public $userName;
    public $message;
    public $hashTags;
    public $urls;

    public function jsonSerialize()
    {
        return [
            'user_name' => $this->userName,
            'message' => $this->message,
            'hash_tags' => $this->hashTags,
            'urls'  => $this->urls,
        ];
    }
}
