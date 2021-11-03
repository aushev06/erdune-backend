<?php

namespace App\Blog\Controllers;

use App\Blog\Resources\UserCollection;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $qb = User::query();

        if ($request->is_specialist) {
            $qb->where('position', '!=', null);
        }

        if (isset($request->ready_for_work)) {
            $qb->where('ready_for_work', $request->ready_for_work == 'true');
        }

        if ($request->positions) {
            $qb->whereIn('position', explode(',', $request->positions));
        }


        return new UserCollection($qb->paginate(10));
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
