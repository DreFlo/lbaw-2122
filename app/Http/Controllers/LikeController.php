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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::table('like')->insert([
            'user_id' => $request->user_id,
            'content_id' => $request->content_id
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        DB::table('like')
            ->where('user_id', $request->user_id)
            ->where('content_id', $request->content_id)
            ->delete();

        return back();
    }
}
