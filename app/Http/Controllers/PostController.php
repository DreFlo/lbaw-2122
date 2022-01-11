<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!Gate::allows('viewAny-content')) {
            abort(403);
        }

        return view('pages.index_posts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        if (!Gate::allows('create-content')) {
            abort(403);
        }

        return view('pages.create_post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|\Illuminate\Http\RedirectResponse|Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        //TODO Validation of request
        if (!Gate::allows('create-content')) {
            abort(403);
        }

        $post_array = [
            'pic_1' => null,
            'pic_2' => null,
            'pic_3' => null,
            'pic_4' => null,
            'pic_5' => null
        ];

        if ($request->hasFile('images')) {
            $i = 1;
            foreach ($request->file('images') as $image) {
                if ($i > 5) break;

                $post_array['pic_'.$i] = Image::storeAndRegister($image);

                $i++;
            }
        }

        $user_content_id = DB::table('user_content')->insertGetId([
            'text' => $request->input('text'),
            'priv_stat' => $request->visibility,
            'creator_id' => $request->user()->id,
            'group_id' => $request->group_id
        ]);

        $post_array['id'] = $user_content_id;

        $post_id = DB::table('post')->insertGetId($post_array);

        if ($request->has('tags')) {
            foreach ($request->tags as $tag) {
                DB::table('tag')->insert([
                    'user_id' => $tag,
                    'content_id' => $post_id
                ]);
            }
        }

        return redirect('posts/'.$post_id);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return Application|Factory|View
     */
    public function show(Post $post)
    {
        if (!Gate::allows('view-content', $post->content)) {
            abort(403);
        }
        return view('pages.post', ['post' => $post]);
    }

    public function share(Post $post)
    {
        return view('pages.share_post', ['post' => $post]);
    }

    public function createInGroup(Group $group) {
        if (!Gate::allows('createIn-group', $group)) {
            abort(403);
        }

        return view('pages.create_post_group', ['group' => $group]);
    }

    public static function search(Request $request){
        $input = $request->input('search');

        $posts = Post::query()
            ->select('sub.*')
            ->selectRaw("ts_rank_cd(to_tsvector(sub.\"text\"), plainto_tsquery('english', ?)) as rank", [$input])
            ->from(
                DB::raw("(select *
                                from (select id
                                      from post) as posts left join user_content ON (posts.id = user_content.id)) as sub"))
            ->whereRaw("(sub.\"text\") @@ plainto_tsquery('english', ?)", [$input])
            ->orderByDesc('rank')
            ->get();
        
        if(Auth::check()){
            $auth_user = Auth::user();
            foreach ($posts as $key => $post){
                if ($post->priv_stat == 'Private'){
                    if($post->creator_id == Auth::id()) continue;
                    elseif (User::find($post->creator_id)->friends->contains($auth_user)) continue;
                    else{
                        $posts->pull($key);
                    }
                }

            }
        }

        return $posts;
    }
}
