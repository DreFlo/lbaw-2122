@extends('layouts.app')

@section('content')
<div class="login_page">
    <div class="login_form">
        <h1>Login</h1>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="login_txt">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
                <label>E-mail</label>
            </div>

            <div class="login_txt">
                <input id="password" type="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
                <label>Password</label>
            </div>

            <input type="submit" value="Login">

            <div class="signup_link">
                <a class="button button-outline" href="{{ route('register') }}">Register</a>
            </div>
        </form>
    </div>
</div>
@endsection
