<?php


namespace App\Blog\Services;


use App\Blog\Enums\StatusEnum;
use App\Blog\Helpers\TextHelper;
use App\Models\Category;
use App\Models\Likeable;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Database\Query\Builder as QB;
use JetBrains\PhpStorm\ArrayShape;

class HomeService
{

    private function getPosts(Request $request): array
    {
        $query = Post::query()->where('status', StatusEnum::STATUS_ACTIVE);

        $query->when($request->new, function (Builder $builder) {
            return $builder->orderByDesc('id');
        }, function (Builder $builder) {
            $builder->orderByDesc('likes_count');
            $builder->orderByDesc('dislikes_count');
            $builder->orderByDesc('views');
            $builder->orderByDesc('comments_count');
            return $builder;
        });

        $query->select([
            'id',
            'title',
            'slug',
            'img',
            'description',
            'views',
            'likes',
            'dislikes',
            'created_at',
            'user_id'
        ]);

        $query->with(['user'])->withCount(['likes', 'dislikes', 'comments']);

        $query->when($request->user('api'), function (Builder $builder, User $user) {
            $builder->addSelect(['liked_type' => function (QB $qb) use ($user) {
                return $qb->selectRaw(Likeable::getUserLikedTypeQuery('posts', 'Post', $user));
            }]);
        });

        return $query->limit(10)->get()->toArray();
    }

    private function getComments()
    {
        return Comment::with('post:id,slug,title')
            ->take(10)
            ->orderByDesc('id')
            ->get()
            ->toArray();
    }

    private function getPopularUsers()
    {
        return User::take(10)
            ->withCount('posts')
            ->orderBy('posts_count', 'DESC')
            ->get()
            ->toArray();
    }

    private function getCategories()
    {
        return User::take(5)
            ->orderByDesc('name')
            ->get()
            ->toArray();
    }

    public function getMainInfo(Request $request)
    {
        $posts = $this->getPosts($request);
        $comments = $this->getComments();
        $categories = $this->getCategories();
        $users = array_map(function($item) {
            $rating = $item['posts_count'] + $item['comments_count'];
            $item['rating'] = $rating;
            return $item;
          }, $this->getPopularUsers());
        
        return response()->json([
          'posts' => $posts,
          'comments' => $comments,
          'categories' => $categories,
          'users' => usort($users, function ($a, $b) {
            return $a['rating'] - $b['rating'];
          }
      ]);
    }

}
