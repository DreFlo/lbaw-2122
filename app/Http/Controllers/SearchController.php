<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $abc = UserController::fts($request);

        $users = UserController::search($request);

        $groups = GroupController::search($request);

        if(Auth::check()){
            $posts = PostController::search($request);
            $comments = CommentController::search($request);

            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => $posts, 'comments' => $comments]);
        }
        else{
            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => null, 'comments' => null]);
        }
    }
}
