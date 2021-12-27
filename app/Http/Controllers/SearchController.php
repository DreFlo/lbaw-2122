<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\UserContent;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $input = $request->input('search');

        $users = User::query()->whereRaw(
            'plainto_tsquery(?) @@ to_tsvector(name)', [$input]
        )->get();
        dd($users);

        return view('pages.searchResults', ['posts' => $results]);
    }
}
