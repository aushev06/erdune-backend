<?php
namespace App\Blog\Services;

use App\Blog\Helpers\TextHelper;
use App\Models\User;
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
        $arr = $request->user()->notifications()->paginate(10);
        return $arr;
    }

    public function readNotifications(Request $request)
    {
      $request->user()->notifications()->where('read_at', '=', null)->update(['read_at' => time()]);
    }
}
