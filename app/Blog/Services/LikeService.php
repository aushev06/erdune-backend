<?php


namespace App\Blog\Services;


use App\Models\Comment;
use App\Models\Likeable;
use App\Models\Post;

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

        Likeable::query()->insert([
            'likeable_type' => static::$types[$type],
            'likeable_id' => $id,
            'type' => $likeType,
            'user_id' => $userId

        ]);

    }
}
