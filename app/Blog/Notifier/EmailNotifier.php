<?php


namespace App\Blog\Notifier;


use Illuminate\Support\Facades\Mail;

class EmailNotifier implements NotifierInterface
{
    public function notify(Message $message)
    {
        // отправка смс
    }
}
