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

            //Sort posts
            if($request->has('sort_post')){
                if($request->input('sort_order_post') == 'asc') $posts = $posts->sortBy($request->input('sort_post'));
                else $posts = $posts->sortByDesc($request->input('sort_post'));
            }

            //Filter posts
            if($request->has('sort_post_type')){
                switch ($request->input('sort_post_type')){
                    case 'all':
                        break;
                    case 'public':
                        foreach ($posts as $key => $post){
                            if ($post->priv_stat != 'Public') $posts->pull($key);
                        }
                        break;
                    case 'private':
                        foreach ($posts as $key => $post){
                            if ($post->priv_stat != 'Private') $posts->pull($key);
                        }
                        break;
                    case 'friends':
                        $auth_user = Auth::user();
                        foreach ($posts as $key => $post){
                            if(!$auth_user->friends->contains(User::find($post->creator_id))) $posts->pull($key);
                        }
                        break;
                }
            }


            //Sort comments
            if($request->has('sort_comment')){
                if($request->input('sort_order_comment') == 'asc') $comments = $comments->sortBy($request->input('sort_comment'));
                else $comments = $comments->sortByDesc($request->input('sort_comment'));
            }

            //Filter comments
            if($request->has('sort_comment_type')){
                switch ($request->input('sort_comment_type')){
                    case 'all':
                        break;
                    case 'public':
                        foreach ($comments as $key => $comment){
                            if ($comment->priv_stat != 'Public') $comments->pull($key);
                        }
                        break;
                    case 'private':
                        foreach ($comments as $key => $comment){
                            if ($comment->priv_stat != 'Private') $comments->pull($key);
                        }
                        break;
                    case 'friends':
                        $auth_user = Auth::user();
                        foreach ($comments as $key => $comment){
                            if(!$auth_user->friends->contains(User::find($comment->creator_id))) $comments->pull($key);
                        }
                        break;
                }
            }

            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => $posts, 'comments' => $comments, 'request' => $request]);
        }
        else{
            return view('pages.searchResults', ['users' => $users, 'groups' => $groups, 'posts' => null, 'comments' => null, 'request' => $request]);
        }

    }
}
