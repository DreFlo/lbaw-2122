<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function show() {
        if (Auth::check()) {
            //get user posts and return posts view
            return view('pages.home'); //for now
        }
        return redirect('login');
    }
}
