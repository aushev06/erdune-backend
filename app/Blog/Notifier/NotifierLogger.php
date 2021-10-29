<?php


namespace App\Blog\Notifier;


use Illuminate\Support\Facades\Log;

class NotifierLogger implements NotifierInterface
{

    public function __construct(NotifierInterface $next, Log $logger)
    {
        $this->next = $next;
        $this->logger = $logger;
    }

    public function notify(Message $message)
    {
        // TODO: Implement notify() method.
    }
}
