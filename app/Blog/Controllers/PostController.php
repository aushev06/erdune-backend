<?php


namespace App\Blog\Controllers;


use App\Blog\Enums\StatusEnum;
use App\Models\Likeable;
use App\Models\Post;
use App\Blog\Requests\SavePostRequest;
use App\Blog\Resources\PostCollection;
use App\Blog\Resources\PostResource;
use App\Blog\Services\PostService;
use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return PostCollection
     */
    public function index(Request $request): PostCollection
    {
        return new PostCollection(
            $this->postService->getPostsQuery($request);
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Post $post
     * @param SavePostRequest $request
     * @return PostResource
     */
    public function store(Post $post, SavePostRequest $request): PostResource
    {
        try {
            return new PostResource($this->postService->save($post, $request));
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception($exception, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(string $slug, Request $request)
    {
        $post = Post::query()->where('slug', $slug)
            ->when($request->user('api'), function (Builder $builder, User $user) {
                $builder->addSelect(['liked_type' => function(QB $qb) use($user) {
                    return $qb->selectRaw(Likeable::getUserLikedTypeQuery('posts', 'Post', $user));
                }]);
            })
            ->first();
        $this->authorize('view', $post);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SavePostRequest $request
     * @param Post $post
     * @return PostResource
     * @throws \Exception
     */
    public function update(SavePostRequest $request, Post $post): PostResource
    {
        try {
            return new PostResource($this->postService->save($post, $request));
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception($exception, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Response
     * @throws \Exception
     */
    public function destroy(Post $post): Response
    {
        try {
            $post->delete();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception($exception, 400);
        }
    }


    public function saveByUrl(Request $request)
    {
        try {
            return $this->postService::saveImage($request);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new \Exception($exception, 400);
        }
    }

    public function getThemes(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Theme::query()->get();
    }

}
