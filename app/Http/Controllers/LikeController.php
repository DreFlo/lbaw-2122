<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('like')->insert([
            'user_id' => $request->user_id,
            'content_id' => $request->content_id
        ]);

        return response('Like registered');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        DB::table('like')
            ->where('user_id', $request->user_id)
            ->where('content_id', $request->content_id)
            ->delete();

        return response('Unlike registered');
    }
}
