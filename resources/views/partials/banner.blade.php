<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<nav class="navbar">
    <a href="{{ route('home') }}" style="text-decoration:none">
        <p class="banner_title"> INFRA </p>
    </a>
    @if (Auth::check()!=true && !\Request::is('login'))
        <a href="{{ route('login') }}">
            <button class="login-button"><span>Login</span></button>
        </a>
    @endif
    <form class="form-inline my-2 my-lg-0" action="/search" method="get">
        <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
    </form>
    @if (Auth::check())
        <div class="dropdown">
            <a href="{{ route('profile') }}">
                @foreach(Auth::User()->getProfilePic() as $pic)
                    <img id="bannerpic" class="dropbtn" src="{{ url($pic->path) }}" />
                @endforeach
            </a>
            <div class="dropdown-content">
                <a href="/notifications">Notifications</a>
                <a href="{{ route('profile/edit') }}">Edit Profile</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('contacts') }}">Contact Us</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    @endif
</nav>



