@extends('layouts.app')

@section('content')
    <h1 class="m-5">
        @if(optional(Auth::user())->id == $user->id)
            <script type="text/javascript">document.getElementById('logout-form').submit();</script>
        @endif
        Deleted Account
    </h1>
@endsection
