<?php


namespace App\Blog\Services;


use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentService
{
    public function save(Comment $comment, FormRequest $formRequest): Comment
    {
        $comment->fill($formRequest->validated());
        $comment->save();

        return $comment;
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
