<div class="header-login-register">
    @auth
        <a href="{{ route('dashboard') }}" class="btn btn-color5 mr1">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-color5 mr1">Login</a>
        <a href="{{ route('register') }}" class="btn btn-color4">Join Now</a>
    @endauth

</div>
