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
            return redirect('/login');
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
            return redirect('/login');
        }

        return view('pages.user_request_notifications', ['friendReqNots' => $user->friendRequestNotifications]);
    }

    public function invite() {
        $user = Auth::user();

        if ($user === null) {
            return redirect('/login');
        }

        return view('pages.invite_notifications', ['groupInvNots' => $user->groupInviteNotifications]);
    }

    public function all() {
        $user = Auth::user();

        if ($user === null) {
            return redirect('/login');
        }

        return view('pages.notifications', [
            'likeNotifications' => $user->likeNotifications,
            'commentNotifications' => $user->commentNotifications(),
            'shareNotifications' => $user->shareNotifications(),
            'tagNotifications' => $user->tagNotifications,
            'friendReqNots' => $user->friendRequestNotifications,
            'groupInvNots' => $user->groupInviteNotifications
        ]);
    }
}
