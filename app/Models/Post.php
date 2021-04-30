<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $description
 * @property string $img
 * @property int $user_id
 * @property string $slug
 * @property string $views
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $status
 * @property int $likes
 * @property int $dislikes
 *
 * @property-read int $comments_count
 *
 * @property User $user
 * @property Comment[] $comments
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        'title',
    ];

    protected $with = ['user'];

    protected $withCount = ['comments'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'id'],
            ]
        ];
    }
}
