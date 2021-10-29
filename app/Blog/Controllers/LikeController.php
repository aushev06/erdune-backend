<?php

namespace App\Blog\Controllers;

use App\Blog\Services\LikeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(private LikeService $likeService)
    {

    }

    public function like(Request $request) {
        $this->likeService->setLike($request->id, $request->type, $request->user('api')->id, $request->like ? $request->like : null);
    }
}
