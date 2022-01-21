<?php

namespace App\Http\Controllers;

use App\Models\Group;

use App\Models\Image;
use App\Models\User;
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
            return view('pages.view_forbidden', ['message' => 'You can\'t view this page as you are not an admin']);
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
        if (!Gate::allows('create-group')) {
            abort(403);
        }

        return view('pages.create_group');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Gate::allows('create-group')) {
            abort(403);
        }

        $cover_pic = null;
        if($request->hasFile('image')) {
            $cover_pic = Image::storeAndRegister($request->file('image'));
        }
        if($cover_pic === null) {
            $group_id = DB::table('group')->insertGetId([
                'name' => $request->input('name'),
                'priv_stat' => $request->visibility,
                'creator_id' => $request->user()->id
            ]);
        }
        else {
            $group_id = DB::table('group')->insertGetId([
                'name' => $request->input('name'),
                'priv_stat' => $request->visibility,
                'cover_pic' => $cover_pic,
                'creator_id' => $request->user()->id
            ]);
        }

        return redirect('groups/'.$group_id);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        if (!Gate::allows('view-group', $group)) {
            abort(403);
        }

        if(isset($group)) {

            $posts = $group->sortedPosts();
            $members=collect($group->members)->chunk(4)->first();
            return view('pages.group', ['group' => $group, 'posts' => $posts, 'members' => $members]);
        }

        return redirect('/');
    }

    public function showMembers(Group $group) {
        if (!Gate::allows('view-group', $group)) {
            abort(403);
        }

        if(isset($group)) {
            $members=$group->sortedMembers();

            return view('pages.members_group', ['group' => $group, 'members' => $members]);
        }
        return redirect('/');
    }

    public function showEdit(Group $group) {
        if (!Gate::allows('update-group', $group)) {
            abort(403);
        }
        if(isset($group)) {
            return view('pages.edit_group', ['group' => $group]);
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
        if (!Gate::allows('update-group', $group)) {
            abort(403);
        }
        if(isset($group)) {
            return view('pages.edit_group', ['group' => $group]);
        }
        return redirect('/');
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

        return redirect(route('profile'));
    }
    public function addMember(Group $group, User $user) {

        if(!Gate::allows('addmember-group', $group)) {
            abort(403);
        }
        DB::table("membership")->insert([
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);

        return redirect(route('group', $group));
    }
    public function removeMember(Group $group, User $user) {

        if(!Gate::allows('addmember-group', $group)) {
            abort(403);
        }
        DB::table("membership")
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();

        return redirect(route('group', $group));

    }

    public static function search(Request $request){
        $input = $request->input('search');

        if(Auth::check()){
            $ts_groups = Group::query()
                ->select('sub.*')
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw("(select \"group\".id as id, \"group\".name as name, \"group\".cover_pic as cover_pic, \"group\".priv_stat as priv_stat
                                from \"group\"
                                group by id ) AS sub"))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->orderByDesc("rank")
                ->get();

            $l_groups = Group::whereRaw(
                "name like '%$input%'"
            )->get();
        }
        else{
            $ts_groups = Group::query()
                ->select('sub.*')
                ->selectRaw("ts_rank_cd(to_tsvector(sub.name), plainto_tsquery('english', ?)) as rank", [$input])
                ->from(
                    DB::raw('(select "group".id as id, "group".name as name, "group".cover_pic as cover_pic, "group".priv_stat as priv_stat
                                from "group"
                                where priv_stat = \'Public\'
                                group by id ) AS sub'))
                ->whereRaw("(sub.name) @@ plainto_tsquery('english', ?)", [$input])
                ->orderByDesc("rank")
                ->get();

            $l_groups = Group::whereRaw(
                "name like '%$input%' and priv_stat = 'Public'"
            )->get();

        }

        $groups = $ts_groups->merge($l_groups);
        foreach ($groups as $key => $group){
            if($group->priv_stat == 'Anonymous') $groups->pull($key);
        }

        return $groups;
    }
}
