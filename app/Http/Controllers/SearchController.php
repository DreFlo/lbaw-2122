<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $input = $request->input('search');

        $users = User::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw('(select "user".id as id, "user".name as name, "user".profile_pic as profile_pic
                                from "user"
                                group by id ) AS sub'))
            ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
            ->orderBy("rank", "DESC")
            ->get();


        return view('pages.searchResults', ['users' => $users]);
    }
}
