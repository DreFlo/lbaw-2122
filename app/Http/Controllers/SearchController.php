<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $input = $request->input('search');

        $users = UserController::search($input);

        return view('pages.searchResults', ['users' => $users]);
    }
}
