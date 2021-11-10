<?php


namespace App\Blog\Services;


use App\Blog\Enums\StatusEnum;
use App\Blog\Helpers\TextHelper;
use App\Models\Category;
use App\Models\Likeable;
use App\Models\Post;
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

class PostService
{
    public function save(Post $post, FormRequest $formRequest): Post
    {
        return DB::transaction(function () use ($post, $formRequest) {
            $slug = $post->title != $formRequest->title ? SlugService::createSlug(Post::class, 'slug', $formRequest->title) : $post->slug;
            $post->fill($formRequest->validated());
            $body = $formRequest->body;
            $post->body = json_encode($body);
            $post->description = $this->getFirstTextFromBody($body);
            $post->user_id = $formRequest->user('api')->id;
            $post->slug = $slug;
            $post->img = $this->getImage($formRequest->body);
            $post->status = $formRequest->status ?? StatusEnum::STATUS_DRAFT;
            $post->category_id = $formRequest->category['id'] ?? null;
            $post->save();

            $themes = collect($formRequest->themes ?? []);

            $themeIds = $themes->map(function ($t) {
                $theme = Theme::firstOrCreate(['name' => htmlspecialchars($t['name'])]);

                return $theme->id;
            });

            $post->themes()->sync($themeIds);

            return Post::where('id', $post->id)->first();
        });
    }

    private function getFirstTextFromBody($body): string {
      foreach ($body as $key => $block) {
          if (isset($block['data']['text'])) {
              return $block['data']['text'];
          }
      }

      return "";
    }

    private function clearHtmlFromBody($body): string
    {
        foreach ($body as $key => $block) {
            if (isset($block['data']['text'])) {
                $body[$key]['data']['text'] = htmlspecialchars($block['data']['text']);
            }
        }

        return json_encode($body);
    }

    private function getImage($body): string
    {
        foreach ($body as $key => $block) {
            if (isset($block['data']['file'])) {
                return $block['data']['file']['url'];
            }
        }

        return "";
    }


    public function getPostsQuery(Request $request)
    {
        $query = Post::query()->when($request->user('api') && $request->status, static function (Builder $builder) use ($request) {
            return $builder
                ->where('status', $request->status)
                ->where('user_id', $request->user('api')->id);
        }, function (Builder $builder) use ($request) {
            return $builder
                ->where('status', StatusEnum::STATUS_ACTIVE)
                ->when($request->user_ids, static function (Builder $subBuilder, string $ids) {
                    return $subBuilder->whereIn('user_id', explode(',', $ids));
                });
        });

        $query->when($request->new, function (Builder $builder) {
          return $builder->orderByDesc('id');
        }, function (Builder $builder) {
          $builder->orderByDesc('likes_count');
          $builder->orderByDesc('dislikes_count');
          $builder->orderByDesc('views');
          $builder->orderByDesc('comments_count');
          return $builder;
        });

        $query->when($request->themes, function (Builder $builder, string $themes) {
            $themes = explode(',', $themes);
            $themes = array_map(fn(string $theme) => '#' . $theme, $themes);
            $builder->whereHas('themes', function (Builder $subBuilder) use ($themes) {
                $subBuilder->whereIn('themes.name', $themes);
            });
        });

        $query->when($request->categories, function (Builder $builder, string $categories) {
            $categories = explode(',', $categories);
            $categoryIds = [];

            if (is_numeric($categories[0])) {
                $categoryIds = Category::query()->select(['id'])->whereIn('id', $categories)->get()->map(fn(Category $category) => $category->id)->toArray();
            } else {
                $categoryIds = Category::query()->select(['id'])->whereIn('slug', $categories)->get()->map(fn(Category $category) => $category->id)->toArray();
            }
            $builder->whereIn('category_id', $categoryIds);
        });


        $query->when($request->user('api'), function (Builder $builder, User $user) {
            $builder->addSelect(['liked_type' => function (QB $qb) use ($user) {
                return $qb->selectRaw(Likeable::getUserLikedTypeQuery('posts', 'Post', $user));
            }]);
        });

        $query->when($request->title, function (Builder $builder, string $title) {
            return $builder->where('title', 'LIKE', "%" . TextHelper::clearHtml($title) . "%");
        });

        return $query->paginate($request->limit || 10);

    }

    #[ArrayShape(['success' => "int", 'file' => "array"])] public static function saveImage(Request $request)
    {
        $url = $request->post('url');
        if ($url) {
            $extension = last(explode('.', $url));
            $filename = implode('.', [time(), $extension]);
            $path = ['app', 'public', 'images', $filename];

            copy($url, implode('/', [app()->storagePath(), ...$path]));
            return [
                'success' => 1,
                'file' => [
                    'url' => implode('/', [config('app.url'), 'storage', 'images', $filename])
                ]
            ];
        }

        return [
            'success' => 1,
            'file' => [
                'url' => str_replace('/public/', '/', implode('/', [config('app.url'), 'storage', $request->file('image')->store('public/images')]))
            ]
        ];

    }

}
