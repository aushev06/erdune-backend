<?php

namespace App\Blog\Controllers;

use App\Blog\Resources\UserCollection;
use App\Blog\Services\UserSearchService;
use App\Blog\Services\UserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct(private UserSearchService $searchService, private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        return new UserCollection($this->searchService->findAllQuery($request)->paginate(10));
    }

    public function show($id) {
        return $this->userService->show($id);
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

    public function getNotifications(Request $request)
    {
        return $this->userService->notifications($request);
    }
    
    public function readNotifications()
    {
        return $this->userService->readNotifications();
    }

}
