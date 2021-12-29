<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.create_post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
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
                $imageName = time() . $i . '.' . $image->extension();
                $image->move(public_path('storage/images'), $imageName);

                $image_id = DB::table('image')->insertGetId([
                    'path' => 'storage\images' . '\\' . $imageName
                ]);

                $post_array['pic_'.$i] = $image_id;

                $i++;
            }
        }

        $user_content_id = DB::table('user_content')->insertGetId([
            'text' => $request->input('text'),
            'priv_stat' => 'Public',
            'creator_id' => 1
        ]);

        $post_array['id'] = $user_content_id;

        $post_id = DB::table('post')->insertGetId($post_array);

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
        if (!Gate::allows('view-post', $post)) {
            abort(403);
        }
        return view('pages.post', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //TODO Auth

        $post->content->delete();

        return response('Deleted.');
    }
}
