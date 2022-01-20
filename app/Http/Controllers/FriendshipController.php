<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FriendshipController extends Controller
{
    public function sendRequest(Request $request) {
        DB::table('friend_request')->insert(
            ['requester_id' => $request->sender_id, 'target_id' => $request->target_id]
        );

        return response('Friendship request sent');
    }

    public function removeFriend(Request $request) {
        DB::table('friendship')
            ->where('user_1', $request->sender_id)
            ->where('user_2', $request->target_id)
            ->delete();

        DB::table('friendship')
            ->where('user_2', $request->sender_id)
            ->where('user_1', $request->target_id)
            ->delete();

        return response('Friendship removed');
    }

    public static function removeFriendUsers(User $user1, User $user2){
        DB::table('friendship')
            ->where('user_1', $user1->id)
            ->where('user_2', $user2->id)
            ->delete();

        DB::table('friendship')
            ->where('user_2', $user2->id)
            ->where('user_1', $user1->id)
            ->delete();
    }
}
