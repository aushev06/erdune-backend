<?php


namespace App\Blog\Services;


use App\Blog\Enums\StatusEnum;
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
class PostService
{
    public function save(Post $post, FormRequest $formRequest): Post
    {
        return DB::transaction(function () use ($post, $formRequest) {
            $post->fill($formRequest->validated());
            $post->body = $this->clearHtmlFromBody($formRequest->body);
            $post->description = htmlspecialchars($formRequest->description);
            $post->user_id = $formRequest->user('api')->id;
            $post->slug = SlugService::createSlug(Post::class, 'slug', $post->title);
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


    public function getPostsQuery(Request $request): Builder
    {
        $query = Post::query()->when($request->user('api') && $request->status, static function (Builder $builder) use ($request) {
            return $builder
                ->where('status', $request->status)
                ->where('user_id', $request->user('api')->id);
        }, function (Builder $builder) use ($request) {
            return $builder
                ->where('status', StatusEnum::STATUS_ACTIVE)
                ->when($request->user_ids, static function(Builder $subBuilder, string $ids) {
                    return $subBuilder->whereIn('user_id', explode(',', $ids));
                });


        });

        $query->when($request->popular, function (Builder $builder, string $popular) {
            $builder->orderByDesc('likes_count');
            $builder->orderByDesc('dislikes_count');
            $builder->orderByDesc('views');
            $builder->orderByDesc('comments_count');
            return $builder;
        }, function (Builder $builder) {
            return $builder->orderByDesc('id');
        });

        $query->when($request->themes, function (Builder $builder, string $themes) {
            $themes = explode(',', $themes);
            $themes = array_map(fn(string $theme) => '#' . $theme, $themes);
            $builder->whereHas('themes', function (Builder $subBuilder) use ($themes) {
                $subBuilder->whereIn('themes.name', $themes);
            });
        });

        $query->when($request->user('api'), function (Builder $builder, User $user) {
            $builder->addSelect(['liked_type' => function(QB $qb) use($user) {
                return $qb->selectRaw(Likeable::getUserLikedTypeQuery('posts', 'Post', $user));
            }]);
        });

        return $query;

    }

}
