@extends('layouts.app')

@section('content')
    <h1><a href="/notifications/post">Post Notifications</a></h1>
    @include('partials.post_notifications')
    <h1><a href="/notifications/request">Friend Request Notifications</a></h1>
    @include('partials.user_request_notifications')
    <h1><a href="/notifications/invite">Group Invite Notifications</a></h1>
    @include('partials.invite_notifications')
@endsection
