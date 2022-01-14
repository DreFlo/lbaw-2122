<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            'creator_id' => $request->user()->id,
            'group_id' => $request->group_id
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

    public static function search(Request $request){
        $input = $request->input('search');

        $comments = Post::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.\"text\"), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw("(select *
                            from (select id
                                  from comment) as posts left join user_content ON (posts.id = user_content.id)) as sub"))
            ->whereRaw("(sub.\"text\") @@ plainto_tsquery('english', ?)", [$input])
            ->orderByDesc('rank')
            ->get();

        $auth_user = Auth::user();
        foreach ($comments as $key => $comment){
            if ($comment->priv_stat == 'Private'){
                if($comment->creator_id == Auth::id()) continue;
                elseif (User::find($comment->creator_id)->friends->contains($auth_user)) continue;
                else $comments->pull($key);
            }

        }

        return $comments;
    }
}
