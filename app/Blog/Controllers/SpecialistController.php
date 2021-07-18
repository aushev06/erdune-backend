<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{

    public function index()
    {
        return User::query()->where('ready_for_work', 1)->paginate();
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
