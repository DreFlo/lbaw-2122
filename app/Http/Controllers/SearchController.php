<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $users = UserController::search($request);

        $groups = GroupController::search($request);

        return view('pages.searchResults', ['users' => $users, 'groups' => $groups]);
    }
}
