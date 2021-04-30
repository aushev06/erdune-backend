<?php


namespace App\Blog\Controllers;


use App\Models\Post;
use App\Blog\Requests\SavePostRequest;
use App\Blog\Resources\PostCollection;
use App\Blog\Resources\PostResource;
use App\Blog\Services\PostService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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
        $query = Post::query();

        if ($request->popular) {
            $query->orderByDesc('likes');
            $query->orderByDesc('dislikes');
            $query->orderByDesc('views');
            $query->orderByDesc('comments_count');
        } else {
            $query->orderByDesc('id');
        }

        return new PostCollection(
            PostResource::collection(
                $query->paginate()
            )
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
            throw new \Exception($exception, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
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
            throw new \Exception($exception, 400);
        }
    }
}
