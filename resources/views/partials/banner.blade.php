<nav class="navbar">
    <p class="banner_title"> INFRA </p>
    <form class="form-inline my-2 my-lg-0" action="/search" method="get">
        <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <!--<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
    </form>
    @if (Auth::check())
        <a href="{{ route('logout') }}" class = "logout_button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif 
</nav>



