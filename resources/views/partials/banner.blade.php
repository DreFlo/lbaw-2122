<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<nav class="navbar">
    <a href="{{ route('home') }}" style="text-decoration:none">
        <p class="banner_title"> INFRA </p>
    </a>
    <form class="form-inline my-2 my-lg-0" action="/search" method="get">
        <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <!--<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
    </form>
    @if (Auth::check())
        <div class="dropdown">
            <a href="{{ route('profile') }}">
                <img id="bannerpic" class="dropbtn" src="../storage/images/blank-profile-picture.png" />
            </a>
            <div class="dropdown-content">
                <a href="{{ route('profile/edit') }}">Edit Profile</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    @endif 
</nav>



