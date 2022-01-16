@extends('layouts.app')

@section('content')
    @include('partials.members_group', ['members' => $members])
@endsection
