<?php

namespace App\Blog\Controllers;

use App\Blog\Requests\SaveCommentRequest;
use App\Blog\Services\CommentService;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{

    public function __construct(private CommentService $service)
    {
    }


    public function index()
    {
        /**
         * @var Comment $topComment
         */
        $topComment = Comment::query()
            ->with('post')
            ->orderByDesc('likes')
            ->orderByDesc('dislikes')
            ->where('created_at', '!=', 'now()')
            ->first();

        /**
         * @var Comment[] $comments
         */
        $comments = Comment::query()
            ->with('post')
            ->where('id', '!=', $topComment->id ?? 0)
            ->take(10)
            ->orderByDesc('id')
            ->get()
            ->toArray();

        return array_merge([$topComment], $comments);
    }


    public function store(SaveCommentRequest $request, Comment $comment): Comment
    {
        try {
            return $this->service->save($comment, $request);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception('Что-то пошло не так :(', 400);
        }
    }


    public function update(SaveCommentRequest $request, Comment $comment): Comment
    {
        try {
            return $this->service->save($comment, $request);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception('Что-то пошло не так :(', 400);
        }
    }

    public function show(Post $post)
    {
        return $post->comments()->orderByDesc('id')->get();
    }

    public function destroy(Comment $comment)
    {
        return $comment->delete();
    }
}
