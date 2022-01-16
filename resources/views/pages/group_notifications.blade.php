@extends('layouts.app')

@section('content')
    @foreach($notifications as $notification)
        <div><a href="/users/{{$notification->user->id}}">{{$notification->user->name}}</a> wants to join your group</div>
        <button class="btn btn-primary accept_member" style="background-color: green" type="button" group_id="{{$notification->group->id}}" user_id="{{$notification->user->id}}" req_not_id="{{$notification->id}}">Accept</button>
        <button class="btn btn-primary deny_member" style="background-color: red" type="button" group_id="{{$notification->group->id}}" user_id="{{$notification->user->id}}" req_not_id="{{$notification->id}}">Deny</button>
    @endforeach
@endsection
