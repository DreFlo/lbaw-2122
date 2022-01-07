@extends('layouts.app')

@section('content')
    @foreach(App\Models\User::all() as $user)
        @include('partials.user_index_element', ['user' => $user])
    @endforeach
@endsection
