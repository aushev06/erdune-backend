<?php


namespace App\Blog\Actions;


use App\Blog\Services\PostService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UploadAction extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     */
    public function __invoke(User $user, Request $request): array
    {
        return PostService::uploadFile($request);
    }
}
