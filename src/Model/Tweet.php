<?php

namespace Model;

class Tweet implements \JsonSerializable
{
    public $userName;
    public $message;

    function jsonSerialize()
    {
        return [
            'user_name' => $this->userName,
            'message' => $this->message,
        ];
    }
}
