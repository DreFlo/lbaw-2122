<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Share;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if (!Gate::allows('share-post', Post::find($request->post_id))) {
            abort(403);
        }

        $user_content_id = DB::table('user_content')->insertGetId([
            'text' => $request->input('text'),
            'priv_stat' => $request->visibility,
            'creator_id' => $request->user()->id
        ]);

        $share_id = DB::table('share')->insertGetId([
            'id' => $user_content_id,
            'post_id' => $request->post_id
        ]);

        if ($request->has('tags')) {
            foreach ($request->tags as $tag) {
                DB::table('tag')->insert([
                    'user_id' => $tag,
                    'content_id' => $share_id
                ]);
            }
        }

        return redirect('shares/'.$share_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Share  $share
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Share $share)
    {
        if(!Gate::allows('view-content', $share->content) ||
            !Gate::allows('view-content', $share->post->content)) {
            abort(403);
        }

        return view('pages.share', ['share' => $share]);
    }
}
