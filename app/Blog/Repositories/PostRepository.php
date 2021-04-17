<?php


namespace App\Blog\Repositories;


use App\Models\Post;

class PostRepository
{
    public function __construct(private Post $model)
    {
    }

    public function findAllWithPaginate(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this
            ->getQuery()
            ->paginate(3);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model::query();
    }

}
