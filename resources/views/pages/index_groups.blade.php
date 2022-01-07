@extends('layouts.app')

@section('content')
    @foreach(App\Models\Group::all() as $group)
        @include('partials.group_index_element', ['group' => $group])
    @endforeach
@endsection
