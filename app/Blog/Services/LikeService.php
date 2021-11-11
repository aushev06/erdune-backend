<?php


namespace App\Blog\Services;


use App\Blog\Notifications\SetLikeOrDislikeNotification;
use App\Models\Comment;
use App\Models\Likeable;
use App\Models\Post;
use App\Models\User;

class LikeService
{
    private static $types = [
        'post' => Post::class,
        'comment' => Comment::class
    ];


    public function setLike(int $id, string $type, int $userId, $likeType = null)
    {
        Likeable::query()
            ->where('likeable_id', $id)
            ->where('likeable_type', static::$types[$type])
            ->where('user_id', $userId)
            ->delete();

        if (!$likeType) {
            return;
        }

        $likeTypeId = Likeable::query()->insertGetId([
            'likeable_type' => static::$types[$type],
            'likeable_id' => $id,
            'type' => $likeType,
            'user_id' => $userId
        ]);

        $user = User::whereId($userId)->first();

        $user->notify(new SetLikeOrDislikeNotification(Likeable::getQueryForNotification()->whereId($likeTypeId)->first()));

        return json_encode($likeTypeId);
    }
}
