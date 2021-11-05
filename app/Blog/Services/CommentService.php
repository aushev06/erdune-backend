<?php


namespace App\Blog\Services;


use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentService
{
    public function save(Comment $comment, FormRequest $formRequest): Comment
    {
        $comment->fill($formRequest->validated());
        $comment->user_id = $formRequest->user('api')->id;

        if ($comment->parent_id && $comment->id && $comment->id === $comment->parent_id) {
            $comment->parent_id = null;
        }

        $comment->save();

        return Comment::query()->where('id', $comment->id)->first();
    }


    public function getTopComment(): ?Comment
    {
        return Comment::query()
            ->with('post')
            ->orderByDesc('likes_count')
            ->orderByDesc('dislikes_count')
            ->where('created_at', '!=', 'now()')
            ->first();
    }

}
