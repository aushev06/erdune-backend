<?php


namespace App\Blog\Services;


use App\Blog\Helpers\TextHelper;
use App\Models\User;
use Illuminate\Http\Request;

class UserSearchService
{
    public function findAllQuery(Request $request)
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

        if ($request->name) {
            $qb->where('name', 'LIKE', "%". TextHelper::clearHtml($request->name) ."%");
        }

        return $qb;

    }
}
