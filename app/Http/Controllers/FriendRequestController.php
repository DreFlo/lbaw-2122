<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendRequestController extends Controller
{
    public function accept(Request $request) {
        DB::table('friend_request')
            ->where('requester_id', $request->sender_id)
            ->where('target_id', $request->target_id)
            ->update(['req_stat' => 'Accepted']);

        DB::table('friend_request_notification')
            ->where('id', $request->req_not_id)
            ->delete();

        return response('Friendship created');
    }

    public function deny(Request $request) {
        DB::table('friend_request')
            ->where('requester_id', $request->sender_id)
            ->where('target_id', $request->target_id)
            ->update(['req_stat' => 'Declined']);
        DB::table('friend_request_notification')
            ->where('id', $request->req_not_id)
            ->delete();
        return response('Friendship denied');
    }
}
