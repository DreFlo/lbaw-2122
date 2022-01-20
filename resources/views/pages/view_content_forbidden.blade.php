@extends('layouts.app')

@section('content')
    <h1>You can't view this content</h1>
    @if(Auth::check())
        @if(auth()->user()->priv_stat !== 'Banned' && auth()->user()->priv_stat !== 'Anonymous')
            <button class="btn btn-primary send_request" style="background-color: green" type="button" target_id="{{$user->id}}" sender_id="{{Auth::user()->id}}">Send Friend Request</button>
        @endif
    @else
        <button class="btn btn-primary" style="background-color: green" type="button" onclick="location.href = '{{route('login')}}';">Login</button>
        <button class="btn btn-primary" style="background-color: green" type="button" onclick="location.href = '{{route('register')}}';">Register</button>
    @endif
@endsection
