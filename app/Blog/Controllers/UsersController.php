<?php

namespace App\Blog\Controllers;

use App\Blog\Resources\UserCollection;
use App\Blog\Services\UserSearchService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct(private UserSearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        return new UserCollection($this->searchService->findAllQuery($request)->paginate(10));
    }

    public function show(User $user) {
        return $user;
    }

    public function categories()
    {
        return DB::table('users')
            ->distinct()
            ->selectRaw('position, count(*) as count')
            ->where('position', '!=', null)
            ->groupBy('position')
            ->get();
    }

}
