<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $users = UserController::search($request);

        $groups = GroupController::search($request);

        //Sort users
        if($request->has('sort_user')){
            if($request->input('sort_order_user') == 'asc') $users = $users->sortBy($request->input('sort_user'));
            else $users = $users->sortByDesc($request->input('sort_user'));
        }

        //Filter users
        if(Auth::check()){
            if($request->has('sort_user_type')){
                switch ($request->input('sort_user_type')){
                    case 'all':
                        break;
                    case 'public':
                        foreach ($users as $key => $user){
                            if ($user->priv_stat != 'Public') $users->pull($key);
                        }
                        break;
                    case 'private':
                        foreach ($users as $key => $user){
                            if ($user->priv_stat != 'Private') $users->pull($key);
                        }
                        break;
                    case 'friends':
                        $auth_user = Auth::user();
                        foreach ($users as $key => $user){
                            if (!$auth_user->friends->contains($user)) $users->pull($key);
                        }
                        break;
                }
            }
        }

        //Sort groups
        if($request->has('sort_group')){
            if($request->input('sort_order_group') == 'asc') $groups = $groups->sortBy($request->input('sort_group'));
            else $groups = $groups->sortByDesc($request->input('sort_group'));
        }

        //Filter groups
        if(Auth::check()){
            if($request->has('sort_group_type')){
                switch ($request->input('sort_group_type')){
                    case 'all':
                        break;
                    case 'public':
                        foreach ($groups as $key => $group){
                            if ($group->priv_stat != 'Public') $groups->pull($key);
                        }
                        break;
                    case 'private':
                        foreach ($groups as $key => $group){
                            if ($group->priv_stat != 'Private') $groups->pull($key);
                        }
                        break;
                    case 'member':
                        $auth_user = Auth::user();
                        foreach ($groups as $key => $group){
                            if (!$auth_user->groups->contains($group)) $groups->pull($key);
                        }
                        break;
                }
            }
        }

        if(Auth::check()){
            $posts = PostController::search($request);
            $comments = CommentController::search($request);

            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => $posts, 'comments' => $comments, 'request' => $request]);
        }
        else{
            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => null, 'comments' => null, 'request' => $request]);
        }

    }
}
