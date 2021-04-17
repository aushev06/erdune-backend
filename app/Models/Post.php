<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property User $user
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        'title',
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
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
