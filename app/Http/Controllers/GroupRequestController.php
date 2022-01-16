<?php

namespace App\Http\Controllers;

use App\Models\GroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupRequestController extends Controller
{
    public function acceptInvite(Request $request) {
        DB::table('group_request')
            ->where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->update(['req_stat' => 'Accepted']);

        DB::table('group_invite_notification')
            ->where('id', $request->inv_not_id)
            ->delete();

        return response('Invite accepted');
    }

    public function denyInvite(Request $request) {
        DB::table('group_request')
            ->where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->update(['req_stat' => 'Declined']);

        DB::table('group_invite_notification')
            ->where('id', $request->inv_not_id)
            ->delete();

        return response('Invite denied');
    }

    public function acceptRequest(Request $request) {
        DB::table('group_request')
            ->where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->update(['req_stat' => 'Accepted']);

        DB::table('group_request_notification')
            ->where('id', $request->req_not_id)
            ->delete();

        return response('Request accepted');
    }

    public function denyRequest(Request $request) {
        DB::table('group_request')
            ->where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->update(['req_stat' => 'Declined']);

        DB::table('group_request_notification')
            ->where('id', $request->req_not_id)
            ->delete();

        return response('Request denied');
    }
}
