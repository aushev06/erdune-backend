<?php


namespace App\Blog\Services;


use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentService
{
    public function save(Comment $comment, FormRequest $formRequest): Comment
    {
        $comment->fill($formRequest->validated());
    }


}
