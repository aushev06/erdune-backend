<?php

namespace App\Observers;

use App\Blog\Notifications\AddCommentNotification;
use App\Blog\Notifications\SetLikeOrDislikeNotification;
use App\Models\Likeable;

class LikeableObserver
{
    /**
     * Handle the Likeable "created" event.
     *
     * @param  \App\Models\Likeable  $likeable
     * @return void
     */
    public function created(Likeable $likeable)
    {
        $user = auth('api')->user();

        $user->notify(new SetLikeOrDislikeNotification($likeable));
    }

    /**
     * Handle the Likeable "updated" event.
     *
     * @param  \App\Models\Likeable  $likeable
     * @return void
     */
    public function updated(Likeable $likeable)
    {
        //
    }

    /**
     * Handle the Likeable "deleted" event.
     *
     * @param  \App\Models\Likeable  $likeable
     * @return void
     */
    public function deleted(Likeable $likeable)
    {
        //
    }

    /**
     * Handle the Likeable "restored" event.
     *
     * @param  \App\Models\Likeable  $likeable
     * @return void
     */
    public function restored(Likeable $likeable)
    {
        //
    }

    /**
     * Handle the Likeable "force deleted" event.
     *
     * @param  \App\Models\Likeable  $likeable
     * @return void
     */
    public function forceDeleted(Likeable $likeable)
    {
        //
    }
}
