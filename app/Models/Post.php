<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $img
 * @property int $user_id
 * @property string $slug
 * @property string $views
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $status
 * @property string $rating
 *
 * @property User $user
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
}
