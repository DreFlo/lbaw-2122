@extends('layouts.app')

@section('content')
    @include('partials.edit_group', ['group' => $group])
@endsection
