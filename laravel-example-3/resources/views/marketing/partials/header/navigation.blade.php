<nav class="navbar navbar-default site-navigation">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            @foreach($headerNavigation as $navigation)
            <li class="{{  isset($navigation['submenu']) ? 'dropdown' : ''}}">
                <a
                class="nav-link dropdown-toggle {{ $navigation['link_class'] ?? '' }}"
                href="{{ $navigation['href'] }}"
                aria-haspopup="true"
                aria-expanded="false">{{ $navigation['label'] }}</a>
                @isset($navigation['submenu'])
                <i
                class="fa fa-caret-down js-dropdown-item-toggle dropdown-item-toggle" aria-hidden="true"></i>

                <ul class="dropdown-menu">
                    @foreach ($navigation['submenu'] as $key => $submenuitem)
                    <li class="dropdown-item">
                        <a
                        href="{{ $submenuitem['href'] }}"
                        class="{{ $submenuitem['link_class'] ?? '' }}">{{ $submenuitem['label'] }}</a>
                    </li>
                    @endforeach
                </ul>
                @endisset
            </li>
            @endforeach
        </ul> {{-- /.navbar-nav --}}
    </div>{{-- /.navbar-collapse --}}
</nav>

<nav class="navbar navbar-default site-navigation login-register-nav">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
        <ul class="nav navbar-nav">
            @auth
                <li><a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a></li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                <li><a href="{{ route('register') }}" class="nav-link">Join Now</a></li>
            @endauth
        </ul> {{-- /.navbar-nav --}}
    </div>{{-- /.navbar-collapse --}}
</nav>
