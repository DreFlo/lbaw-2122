@extends('layouts.app')

@section('content')

    <table> 
    @foreach(App\Models\Group::all() as $group)
        @include('partials.group_index_element', ['group' => $group])
    @endforeach
    </table>
@endsection
