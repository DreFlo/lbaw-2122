<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-content')) {
            abort(403);
        }

        $user_content_id = DB::table('user_content')->insertGetId([
           'text' => $request->text,
            'priv_stat' => 'Public',
            'creator_id' => $request->user()->id
        ]);

        DB::table('comment')->insert([
            'id' => $user_content_id,
            'parent_id' => $request->parent_id
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        if (!Gate::allows('view-content', $comment->content)) {
            abort(403);
        }

        return view('pages.comment', ['comment' => $comment]);
    }
}
