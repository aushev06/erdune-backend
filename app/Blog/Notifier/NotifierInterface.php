<?php


namespace App\Blog\Notifier;


interface NotifierInterface
{
    public function notify(Message $message);
}
