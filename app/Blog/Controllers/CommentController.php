<?php

namespace App\Blog\Controllers;

use App\Blog\Events\CommentEvent;
use App\Blog\Events\ListCommentEvent;
use App\Blog\Requests\SaveCommentRequest;
use App\Blog\Services\CommentService;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{

    public function __construct(private CommentService $service) { }


    public function index(Request $request)
    {
        $topComment = $this->service->getTopComment();

        return $topComment ?
            array_merge([$topComment], $this->service->getCommentsWithoutTopComment($request, $topComment))
            : $this->service->getCommentsWithoutTopComment($request);
    }

    public function store(SaveCommentRequest $request, Comment $comment): Comment
    {
        $this->save($request, $comment);
    }


    public function update(SaveCommentRequest $request, Comment $comment): Comment
    {
       $this->save($request, $comment);
    }

    public function show(Post $post, Request $request)
    {
        return $this->service->show($post, $request);
    }

    public function destroy(Comment $comment)
    {
        return $comment->delete();
    }

    private function save(FormRequest $request, Comment $comment) {
        try {
            $isUpdated = (bool)$comment->id;
            $savedComment = $this->service->save($comment, $request);

            event(new CommentEvent($savedComment, $isUpdated));
            event(new ListCommentEvent($savedComment));

            return $savedComment;
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception('Что-то пошло не так :(', 400);
        }
    }
}
