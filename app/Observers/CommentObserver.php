<?php

namespace App\Observers;

use App\Blog\Notifications\AddCommentNotification;
use App\Models\Comment;
use Illuminate\Support\Facades\App;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        if (!App::runningInConsole()) {
            if($comment->user_id_reply === null){ // Кто-то прокомментил пост
                $this->sendNotification($comment->post->user, new AddCommentNotification(Comment::getQueryForNotification()->whereId($comment->id)->first()), auth()->user()->id);
            }
        }

    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }

    public function sendNotification($user, AddCommentNotification $notification, $userID) {
        if(true) {
            $user->notify($notification);
        }
    }
}
