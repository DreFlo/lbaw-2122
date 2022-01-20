<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class UserController extends Controller
{
    public function index() {
        if(!Gate::allows('viewAny-user')) {
            abort(403);
        }

        return view('pages.index_users');
    }

    public function profile() {
        if (!Auth::check()) {
            return redirect('/');
        }

        return redirect('users/' . Auth::id());
    }

    public function show(int $id) {
        $user = User::find($id);

        if ($user == null) {
            return back();
        }

        if (!Gate::allows('view-user', $user)) {
            return view('pages.view_user_forbidden', ['user' => $user]);
        }

        if (isset($user)) {
            return view('pages.profile', ['user' => $user]);
        }
        return redirect('/');
    }

    public function showEdit() {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        return view('pages.edit_profile', ['user' => $user]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->birthdate = $request->input('birthdate');

        if ($request->file('profile-image') != null) {
            $user->profile_pic = Image::storeAndRegister($request->file('profile-image'));
        }

        if ($request->file('cover-image') != null) {
            $user->cover_pic = Image::storeAndRegister($request->file('cover-image'));
        }

        $user->save();

        return redirect("/profile/edit");
    }

    private static function search_public_users(Request $request){
        $input = $request->input('search');

        $users = User::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw('(select "user".id as id, "user".name as name, "user".profile_pic as profile_pic
                                from "user"
                                where priv_stat = \'Public\'
                                group by id ) AS sub'))
            ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
            ->get();

        return $users;
    }

    public static function searchAux(Request $request){
        $input = $request->searchString;

        $users = User::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw('(select "user".id as id, "user".name as name, "user".profile_pic as profile_pic
                                from "user"
                                where priv_stat = \'Public\'
                                group by id ) AS sub'))
            ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
            ->get();

        if(Auth::check()){
            $id = Auth::id();
            $private_users = User::query()
                ->select('sub.*')
                ->distinct()
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw("(select id, name, profile_pic
                                from (select user_2
                                      from friendship
                                      where user_1 = '$id') as friends left join \"user\" on (friends.user_2 = \"user\".id)) as sub"))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->get();
            $users = $private_users->merge($users);
        }
        $users = $users->sortByDesc("rank");

        return $users;
    }

    public static function search(Request $request){
        $input = $request->input('search');

        if(Auth::check()){
            $ts_users = User::query()
                ->select('sub.*')
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw('(select "user".id as id, "user".name as name, "user".profile_pic as profile_pic
                                from "user"
                                group by id ) AS sub'))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->orderByDesc("rank")
                ->get();

            $l_users = User::whereRaw(
                "name like '%$input%'"
            )->get();

            $users = $ts_users->merge($l_users);

            foreach($users as $key => $user){
                if($user->priv_stat == 'Anonymous') $users->pull($key);
            }
        }
        else{
            $ts_users = User::query()
                ->select('sub.*')
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw('(select "user".id as id, "user".name as name, "user".profile_pic as profile_pic
                                from "user"
                                where priv_stat = \'Public\'
                                group by id ) AS sub'))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->orderByDesc("rank")
                ->get();

            $l_users = User::whereRaw(
                "name like '%$input%' and priv_stat = 'Public'"
            )->get();

            $users = $ts_users->merge($l_users);
        }
        return $users;

    }

    public function ban(Request $request) {
        $policy = new UserPolicy();
        $admin = User::find($request->admin_id);
        $user = User::find($request->user_id);
        if (!$policy->handleBan($admin, $user)) {
            abort(403);
        }

        $user->priv_stat = 'Banned';
        $user->save();

        return response('Banned');
    }

    public function unban(Request $request) {
        $policy = new UserPolicy();
        $admin = User::find($request->admin_id);
        $user = User::find($request->user_id);
        if (!$policy->handleBan($admin, $user)) {
            abort(403);
        }

        $user->priv_stat = 'Private';
        $user->save();

        return response('Unbanned');
    }

    public function destroy(User $user)
    {
        if (!Gate::allows('delete-user', $user)) {
            abort(403);
        }
        $user->priv_stat = 'Anonymous';
        $user->name = 'Deleted Account';
        $user->email = $user->email . 'deleted' . $user->id;

        $user->save();

        return back();
    }
}
