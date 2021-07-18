<?php


namespace App\Blog\Services;


use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class PostService
{
    public function save(Post $post, FormRequest $formRequest): Post
    {
        $post->fill($formRequest->post());
        $post->body        = $this->clearHtmlFromBody($formRequest->body);
        $post->description = htmlspecialchars($formRequest->description);
        $post->user_id     = $formRequest->user()->id;
        $post->slug        = SlugService::createSlug(Post::class, 'slug', $post->title);
        $post->img         = $this->getImage($formRequest->body);
        $post->save();

        return $post;
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

    private function getImage($body): ?string
    {
        foreach ($body as $key => $block) {
            if (isset($block['data']['file'])) {
                return $block['data']['file']['url'];
            }
        }
    }

}
