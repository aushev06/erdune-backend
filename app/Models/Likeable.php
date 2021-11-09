<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likeable extends BaseModel
{
    use HasFactory;

    public function likeable()
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'likeable_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'likeable_id', 'id');
    }

    public static function getQueryForNotification(): \Illuminate\Database\Eloquent\Builder|Comment
    {
        return parent::getQueryForNotification()->with(['user:name,avatar,id', 'post:title,slug,id', 'comment:id,text']);
    }
}
