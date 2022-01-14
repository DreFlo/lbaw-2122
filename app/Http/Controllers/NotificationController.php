<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function post() {
        $user = Auth::user();

        if ($user === null) {
            abort(403);
        }

        return view('pages.post_notifications', [
            'likeNotifications' => $user->likeNotifications,
            'commentNotifications' => $user->commentNotifications(),
            'shareNotifications' => $user->shareNotifications(),
            'tagNotifications' => $user->tagNotifications
        ]);
    }

    public function request() {
        $user = Auth::user();

        if ($user === null) {
            abort(403);
        }

        return view('pages.user_request_notifications', ['friendReqNots' => $user->friendRequestNotifications]);
    }
}
