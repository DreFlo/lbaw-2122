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
        return view('pages.home');
    }

    public function showContacts() {
        return view('pages.contacts');
    }

    public function showAbout() {
        return view('pages.about');
    }
}
