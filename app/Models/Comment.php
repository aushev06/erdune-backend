<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @property int $id
 * @property string $text
 * @property int $user_id
 * @property int $post_id
 * @property int $user_id_reply
 * @property int $parent_id
 * @property int $likes
 * @property int $dislikes
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'post_id'];

    protected $with = ['user'];

    protected $withCount = ['likes', 'dislikes'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function likes()
    {
        return $this->morphOne(Likeable::class, 'likeable')->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->morphOne(Likeable::class, 'likeable')->where('type', 'dislike');
    }

}
