<?php
namespace App\Blog\Services;

use App\Blog\Helpers\TextHelper;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function show($id)
    {
        $user = User::where('id', $id)->with(['posts', 'comments'])->get();
        return $user;
    }
}
