<?php


namespace App\Blog\Services;


use App\Blog\Helpers\TextHelper;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Query\Builder as QB;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Likeable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Post;

class CommentService
{
    public function save(Comment $comment, FormRequest $formRequest): Comment
    {
        $comment->fill($formRequest->validated());
        $comment->user_id = $formRequest->user('api')->id;
        $comment->text = $comment->text ?? "";
        if ($comment->parent_id && $comment->id && $comment->id === $comment->parent_id) {
            $comment->parent_id = null;
        }

        $comment->save();

        return $this->findOne($comment->id, $formRequest->user('api'));
    }

    public function show(Post $post, Request $request)
    {
      $query = $post->comments();
      $user = $request->user('api');

      if ($user) {
        $query->addSelect(['liked_type' => function (QueryBuilder $builder) use ($user) {
            return $builder->selectRaw(Likeable::getUserLikedTypeQuery('comments', 'Comment', $user));
        }]);
      }

      $query->when($request->orderBy, function (Builder $builder) use ($request) {
        if ($request->orderBy === 'popular') {
          return $builder->orderByDesc('likes_count')->orderByDesc('dislikes_count');
        } else {
          return $builder->orderByDesc('id');
        }
      });

      return $query->with(['comments' => static function ($builder) use ($user) {
          if ($user) {
              $builder->addSelect(['liked_type' => function (QueryBuilder $builder) use ($user) {
                  return $builder->selectRaw(Likeable::getUserLikedTypeQuery('comments', 'Comment', $user));
              }]);
          }
      }])->where('parent_id', null)->get();
    }

    public function getTopComment(): ?Comment
    {
        return Comment::query()
            ->with('post')
            ->orderByDesc('likes_count')
            ->orderByDesc('dislikes_count')
            ->where('created_at', '=', 'now()')
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
            ->when($request->text, static function (Builder $builder, string $text) {
                return $builder->where('text', 'LIKE', "%" . TextHelper::clearHtml($text) . "%");
            })
            ->take(10)
            ->orderByDesc('id')
            ->get()
            ->toArray();
    }

    public function findOne(int $id, ?User $user): ?Comment
    {
        return Comment::query()
            ->with(['post', 'comments'])
            ->when($user, static function (Builder $builder, $user) {
                return $builder->addSelect(['liked_type' => function (QB $q) use ($user) {
                    return $q->selectRaw(Likeable::getUserLikedTypeQuery('comments', 'Comment', $user));
                }]);
            })
            ->whereId($id)
            ->first();
    }


}
