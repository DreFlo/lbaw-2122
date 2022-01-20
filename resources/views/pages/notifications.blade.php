@extends('layouts.app')

@section('content')
    <div class="dropdown-notifs" id="dropdown-notifs-post">
        <button onclick="dropdownPostFunction()" class="dropbtn-notifs">Post Nofitications</button>
        <div id="myDropdown-notifs-post" class="dropdown-content-notifs">
            <!-- <h1><a href="/notifications/post">Post Notifications</a></h1> -->
            @include('partials.post_notifications')
        </div>
    </div>

    <div class="dropdown-notifs" id="dropdown-notifs-friendship">
        <button onclick="dropdownFriendshipFunction()" class="dropbtn-notifs">Friendship Nofitications</button>
        <div id="myDropdown-notifs-friendship" class="dropdown-content-notifs">
            <!-- <h1><a href="/notifications/request">Friend Request Notifications</a></h1> -->
            @include('partials.user_request_notifications')
        </div>
    </div>

    <div class="dropdown-notifs" id="dropdown-notifs-groups">
        <button onclick="dropdownGroupsFunction()" class="dropbtn-notifs">Groups Nofitications</button>
        <div id="myDropdown-notifs-groups" class="dropdown-content-notifs">
            <!-- <h1><a href="/notifications/invite">Group Invite Notifications</a></h1> -->
            @include('partials.invite_notifications')
        </div>
    </div>
@endsection
