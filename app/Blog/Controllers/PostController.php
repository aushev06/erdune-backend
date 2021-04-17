<?php


namespace App\Blog\Controllers;


use App\Blog\Repositories\PostRepository;
use App\Blog\Requests\SavePostRequest;
use App\Blog\Resourses\PostCollection;
use App\Blog\Services\PostService;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        return new PostCollection($this->postService->findAll());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Post $post
     * @param SavePostRequest $request
     * @return Post
     */
    public function store(Post $post, SavePostRequest $request): Post
    {
        try {
            return $this->postService->save($post, $request);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
