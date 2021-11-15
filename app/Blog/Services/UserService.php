<?php
namespace App\Blog\Services;

use App\Blog\Helpers\TextHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserService
{
    public function show($id)
    {
        $user = User::where('id', $id)->with(['posts', 'comments', 'comments.post'])->first();
        return $user;
    }

    public function notifications(Request $request)
    {
        return $request->user('api')->notifications()->orderByDesc('created_at')->paginate(10);
    }

    public function readNotifications(Request $request)
    {
      $request->user('api')->notifications()->where('read_at', '=', null)->update(['read_at' => Carbon::now()]);
    }
}
