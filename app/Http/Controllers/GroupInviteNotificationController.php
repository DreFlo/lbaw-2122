<?php

namespace App\Http\Controllers;

use App\Models\GroupInviteNotification;
use Illuminate\Http\Request;

class GroupInviteNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupInviteNotification  $groupInviteNotification
     * @return \Illuminate\Http\Response
     */
    public function show(GroupInviteNotification $groupInviteNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupInviteNotification  $groupInviteNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupInviteNotification $groupInviteNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupInviteNotification  $groupInviteNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupInviteNotification $groupInviteNotification)
    {
        $this->authorize('update', )
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupInviteNotification  $groupInviteNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupInviteNotification $groupInviteNotification)
    {
        //
    }
}
