@extends('layouts.app')

@section('content')
    @if(count($users) > 0)
        <h2>Users</h2>
        @each('partials.post', $posts, 'post')
    @endif
@endsection
