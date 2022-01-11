@extends('layouts.app')

@section('content')
    @include('partials.create_post', ['group' => $group])
@endsection
