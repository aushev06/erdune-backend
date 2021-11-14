<?php
namespace App\Blog\Services;

use App\Blog\Helpers\TextHelper;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class UserService
{
    public function show($id)
    {
        $user = User::where('id', $id)->with(['posts', 'comments', 'comments.post'])->first();
        return $user;
    }

    public function notifications($userId)
    {
        $arr = Notification::where('data.user_id', $userId)->get()->toArray();
        return $arr;
    }
}
