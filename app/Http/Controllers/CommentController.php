<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Gate::allows('create-content')) {
            return back();
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
     * @param Comment $comment
     * @return Application|Factory|View
     */
    public function show(Comment $comment)
    {
        if (!Gate::allows('view-content', $comment->content)) {
            return view('pages.view_content_forbidden', ['user' => $comment->content->creator]);
        }

        return view('pages.comment', ['comment' => $comment]);
    }

    public static function search(Request $request){
        $input = $request->input('search');

        $ts_comments = Comment::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.\"text\"), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw("(select *, 0 as likes
                            from (select id
                                  from comment) as comments left join user_content ON (comments.id = user_content.id)) as sub"))
            ->whereRaw("(sub.\"text\") @@ plainto_tsquery('english', ?)", [$input])
            ->orderByDesc('rank')
            ->get();

        $l_comments = Comment::query()
            ->select('sub.*')
            ->from(
                DB::raw("(select *, 0 as likes
                            from (select id
                                  from comment) as comments left join user_content ON (comments.id = user_content.id)) as sub"))
            ->whereRaw("sub.\"text\" like '%$input%'")
            ->get();

        $comments = $ts_comments->merge($l_comments);

        $auth_user = Auth::user();
        foreach ($comments as $key => $comment){
            if ($comment->priv_stat == 'Private'){
                if($comment->creator_id == Auth::id()) continue;
                elseif (User::find($comment->creator_id)->friends->contains($auth_user)) continue;
                else $comments->pull($key);
            }
            elseif ($comment->priv_stat == 'Anonymous')
            {
                $comment->pull($key);
            }

            $comment->likes = $comment->content->likeCount();
        }

        return $comments;
    }
}
