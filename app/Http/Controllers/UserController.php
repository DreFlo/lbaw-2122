<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class UserController extends Controller
{
    public function profile() {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (isset($user)) {
            $posts = $user->posts()->simplePaginate(7);

            return view('pages.profile', ['user' => $user, 'posts' => $posts]);
        }
    }

    public function show(int $id) {
        if (!Auth::check())
            return redirect('/login');

        $user = User::find($id);
        if (isset($user)) {
            $posts = $user->posts()->simplePaginate(7);

            return view('pages.profile', ['user' => $user, 'posts' => $posts]);
        }
        return redirect('/login');
    }

    public function showEdit() {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        return view('pages.edit_profile', ['user' => $user]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        $user->name = $request->input('name');
        $user->birthdate = $request->input('birthdate');

        if ($request->file('image') != null) {
            $path = $request->file('image')->store('images/profile');
            $image = new Image();
            $image->path = $path;
            $image->save();

            $user->profile_pic = $image->id;
        }

        $user->save();

        return redirect("/profile/edit");
    }
}
