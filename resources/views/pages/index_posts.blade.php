@extends('layouts.app')

@section('content')
    @foreach(App\Models\Post::all() as $post)
        @include('partials.post', ['post' => $post, 'style' => ''])
    @endforeach
@endsection
