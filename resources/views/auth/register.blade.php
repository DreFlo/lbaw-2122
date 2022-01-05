@extends('layouts.app')

@section('content')
<div class="reg_page">
  <div class="reg_form">
    <h2>Registration</h2>
    <form method="POST" enctype="multipart/form-data" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="reg_txt">
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
          @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
          @endif
          <label for="name">Name</label>
        </div>

        <div class="reg_txt">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required>
          @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
          @endif
          <label for="email">E-Mail Address</label>
        </div>

        <div class="reg_txt">
          <input id="password" type="password" name="password" required>
          @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
          @endif
          <label for="password">Password</label>
        </div>

        <div class="reg_txt">
          <input id="password_confirmation" type="password" name="password_confirmation" required>
          @if ($errors->has('password_confirmation'))
            <span class="error">
                {{ $errors->first('password_confirmation') }}
            </span>
          @endif
          <label for="password_confirmation">Confirm Password</label>
        </div>

        <div class="reg_txt">
          <input id="birthdate" type="date" name="birthdate" required>
          @if ($errors->has('birthdate'))
          <span class="error">
                {{ $errors->first('birthdate') }}
            </span>
          @endif
          <label for="birthdate">Birthdate</label>
        </div>

        <div class="reg_txt">
          <select id="priv_stat" name="priv_stat" required>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
          </select>
          @if ($errors->has('priv_stat'))
          <span class="error">
                {{ $errors->first('priv_stat') }}
            </span>
          @endif
          <label for="priv_stat">Privacy Status</label>
        </div>

        <div class="reg_txt">
          <input type="file" id="profile_pic" name="profile_pic" accept=".jpg,.png,.jpeg,.gif,.svg" required>
          @if ($errors->has('profile_pic'))
          <span class="error">
                {{ $errors->first('profile_pic') }}
            </span>
          @endif
          <label for="profile_pic">Profile Picture</label>
        </div>

        <div class="reg_txt">
          <input type="file" id="cover_pic" name="cover_pic" accept=".jpg,.png,.jpeg,.gif,.svg" required>
          @if ($errors->has('cover_pic'))
          <span class="error">
                {{ $errors->first('cover_pic') }}
            </span>
          @endif
          <label for="cover_pic">Cover Picture</label>
        </div>

        <input type="submit" value="Sign-Up">
    
        <div class="reg_link">
          <a class="button button-outline" href="{{ route('login') }}">Go Back</a>
        </div>
    </form>
  </div>
</div>
@endsection
