<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
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
     * @param  \App\Models\Share  $share
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Share $share)
    {
        if(!Gate::allows('view-content', $share->content ||
            !Gate::allows('view-content', $share->post->content))) {
            abort(403);
        }

        return view('pages.share', ['share' => $share]);
    }
}
