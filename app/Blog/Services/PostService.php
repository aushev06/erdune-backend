<?php


namespace App\Blog\Services;


use App\Blog\Repositories\PostRepository;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class PostService
{
    public function __construct(private PostRepository $repository)
    {
    }

    public function save(Post $post, FormRequest $formRequest): Post
    {
        $post->fill($formRequest->post());
        $post->body        = htmlspecialchars($formRequest->body);
        $post->description = htmlspecialchars($formRequest->description);
        $post->user_id     = $formRequest->user()->id;
        $post->slug        = SlugService::createSlug(Post::class, 'slug', $post->title);
        $post->save();

        return $post;
    }

    public function findAll(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->repository->findAllWithPaginate();
    }

}
