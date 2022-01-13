<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('viewAny-group')) {
            abort(403);
        }

        return view('pages.index_groups');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        if(isset($group)) {
            $posts = $group->posts();
            
            $members = [];
            foreach($group->members as $member) {
                array_push($members, $member);
                if(count($members) >= 4) break;
            }
            
            return view('pages.group', ['group' => $group, 'posts' => $posts, 'members' => $members]);
        }

        return redirect('/');
    }

    public function showMembers(Group $group) {
        if(isset($group)) {
            $members = $group->members();
            ddd($members);
            return view('pages.members_group', ['members' => $members]);
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        if(!Gate::allows('delete-group', $group)) {
            abort(403);
        }

        $group->priv_stat = 'Anonymous';
        $group->save();

        return back();
    }

    public static function search(Request $request){
        $input = $request->input('search');

        $groups = Group::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw('(select "group".id as id, "group".name as name, "group".cover_pic as cover_pic, "group".priv_stat as priv_stat, False as belonging
                                from "group"
                                where priv_stat = \'Public\'
                                group by id ) AS sub'))
            ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
            ->get();

        if(Auth::check()){
            $id = Auth::id();
            $private_groups = Group::query()
                ->select('sub.*')
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw("(select \"group\".id as id, \"group\".name as name, \"group\".cover_pic as cover_pic, \"group\".priv_stat as priv_stat, False as belonging
                                from \"group\"
                                where priv_stat = 'Private'
                                group by id ) AS sub"))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->get();

            $auth_user = Auth::user();
            foreach ($private_groups as $group){
                if($auth_user->groups->contains($group)){
                    $group->belonging = True;
                }
            }

            $groups = $private_groups->merge($groups);
        }
        $groups = $groups->sortByDesc("rank");

        return $groups;
    }
}
