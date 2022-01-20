@extends('layouts.app')

@section('content')
    <h1>You can't view this user's profile as they are private</h1>
    @if(Auth::check())
        <button class="btn btn-primary send_request" style="background-color: green" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Send Friend Request</button>
    @else
        <button class="btn btn-primary" style="background-color: green" type="button" onclick="location.href = '{{route('login')}}';">Login</button>
        <button class="btn btn-primary" style="background-color: green" type="button" onclick="location.href = '{{route('register')}}';">Register</button>
    @endif
@endsection
