<?php


namespace App\Blog\Services;


use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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

    public function getCommentsWithoutTopComment(Request $request, ?Comment $topComment = null): array
    {
        return Comment::query()
            ->with('post')
            ->when($topComment?->id, static function (Builder $builder, int $id) {
                return $builder->where('id', '!=', $id);
            })
            ->when($request->user_ids, static function (Builder $builder, string $ids) {
                return $builder->whereIn('user_id', explode(',', $ids));
            })
            ->take(10)
            ->orderByDesc('id')
            ->get()
            ->toArray();
    }


}
