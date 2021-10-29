<?php


namespace App\Blog\Actions;


use App\Blog\Requests\SaveUserRequest;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileAction extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function __invoke(SaveUserRequest $request)
    {
        $request->user('api')->update($request->validated());
        return User::where('id', $request->user('api')->id)->first();
    }

}
