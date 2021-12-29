@extends('layouts.app')

@section('content')
<div class="auth_page">
  <div class="auth_form">
    <h1>Registration</h1>
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="auth_txt">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
          @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
          @endif
          <label for="name">Name</label>
        </div>

        <div class="auth_txt">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required>
          @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
          @endif
          <label for="email">E-Mail Address</label>
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

        <div class="auth_txt">
          <input id="password-confirm" type="password" name="password_confirmation" required>
          <label for="password-confirm">Confirm Password</label>
        </div>

        <div class="auth_txt">
          <input id="birthdate" type="date" name="birthdate" required>
          <label for="birthdate">Birthdate</label>
        </div>

        <input type="submit" value="Sign-Up">
    
        <div class="auth_link">
          <a class="button button-outline" href="{{ route('login') }}">Go Back</a>
        </div>
    </form>
  </div>
</div>
@endsection
