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
        return json_decode('[{"id":5,"name":"\u0414\u0438\u0437\u0430\u0439\u043d \u0437\u0430 \u0447\u0430\u0435\u043c","slug":"design-over-tea","created_at":null,"updated_at":null},{"id":4,"name":"Dev Review","slug":"dev-review","created_at":null,"updated_at":null},{"id":1,"name":"Dev Battle","slug":"dev-battle","created_at":null,"updated_at":null},{"id":3,"name":"Design Review","slug":"design-review","created_at":null,"updated_at":null},{"id":2,"name":"Design Battle","slug":"design-battle","created_at":null,"updated_at":null}]');
    }

    private function getCategories()
    {
        return Category::take(10)
            ->orderByDesc('name')
            ->get()
            ->toArray();
    }

    public function getMainInfo(Request $request)
    {
        $posts = $this->getPosts($request);
        $comments = $this->getComments();
        $categories = $this->getCategories();

        return response()->json([
          'posts' => $posts,
          'comments' => $comments,
          'categories' => $categories
      ]);
    }

}
