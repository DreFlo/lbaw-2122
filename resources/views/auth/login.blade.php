@extends('layouts.app')

@section('content')
<div class="auth_page">
    <div class="auth_form">
        <h1>Login</h1>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="auth_txt">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
                <label for="email">E-mail</label>
            </div>

            <div class="auth_txt">
                <input id="password" type="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
                <label for="password">Password</label>
            </div>

            <input type="submit" value="Login">

            <div class="auth_link">
                <a class="button button-outline" href="{{ route('register') }}">Register</a>
            </div>

            <div class="auth_link">
                <a class="button button-outline" href="{{ route('forget.password.get') }}">Forgot Password?</a>
            </div>
        </form>
    </div>

    <a href="{{ route('about') }}">
        <button class="login-about-button"><span>About Us </span></button>
    </a>

    <a href="{{ route('contacts') }}">
        <button class="login-contacts-button"><span>Contact Us </span></button>
    </a>

    <a href="{{ route('faq') }}" style="position: absolute; left: 60%; bottom: 0;">
        <button class="login-contacts-button"><span>FAQ</span></button>
    </a>
</div>
@endsection
