<?php

namespace App\Http\Controllers;

use App\Models\UserContent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;

class UserContentController extends Controller
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
     * @param UserContent $userContent
     * @return Application|Redirector|RedirectResponse
     */
    public function show(UserContent $userContent)
    {
        if (!Gate::allows('view-content', $userContent)) {
            abort(403);
        }
        if ($userContent->isPost()) return redirect('posts/'.$userContent->id);
        elseif ($userContent->isComment()) return redirect('comments/'.$userContent->id);
        elseif ($userContent->isShare()) return redirect('shares/'.$userContent->id);
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserContent $userContent
     * @return \Illuminate\Http\Response
     */
    public function edit(UserContent $userContent): \Illuminate\Http\Response
    {
        return view('pages.edit_user_content', ['content' => $userContent]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param UserContent $userContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserContent $userContent)
    {
        $userContent->text = $request->text;

        $userContent->priv_stat = $request->visibility;

        $userContent->edited = true;

        $userContent->save();

        return redirect('user_content/'.$userContent->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserContent $userContent
     * @return string
     */
    public function destroy(UserContent $userContent)
    {
        $userContent->priv_stat = 'Anonymous';
        $userContent->save();

        return back();
    }
}
