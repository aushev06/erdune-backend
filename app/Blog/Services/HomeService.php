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

    private function getPosts(Request $request) {
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

      $query->get([
        'id',
        'title',
        'slug',
        'img',
        'description',
        'views',
        'likes',
        'dislikes',
        'comments_count',
        'created_at',
        'user'
      ]);

      $query->when($request->user('api'), function (Builder $builder, User $user) {
          $builder->addSelect(['liked_type' => function (QB $qb) use ($user) {
              return $qb->selectRaw(Likeable::getUserLikedTypeQuery('posts', 'Post', $user));
          }]);
      });

      return $query->paginate(10);
    }

    private function getComments() {
      return Comment::with('post:id,slug,title')
        ->take(10)
        ->orderByDesc('id')
        ->get();
    }

    private function getCategories() {
      return Category::take(10)
        ->orderByDesc('name')
        ->get();
    }

    public function getMainInfo(Request $request) {
      $posts = $this->getPosts($request);
      $comments = $this->getComments();
      $categories = $this->getCategories();
      
      return dd([
        'posts' => $posts,
        'comments' => $comments,
        'categories' => $categories
      ]);
    }

}
