<?php


namespace App\Blog\Notifier;


class Message
{
    public function __construct(public string $to, public string $body)
    {
    }
}
