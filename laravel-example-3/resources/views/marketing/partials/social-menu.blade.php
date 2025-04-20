<nav class="social-menu-navigation">
    <ul class="social-menu list-unstyled clearfix">
        @foreach($socialMenu as $menu)
        <li class="{{ $menu['class'] }}">
            <a
            href="{{ $menu['href'] }}"
            class="fa {{ $menu['icon_class'] }} js-speed-bump-ignore"
            target="_blank">
                <span class="sr-only">{{ $menu['label'] }}</span>
            </a>
        </li>
        @endforeach
    </ul> {{-- /.navbar-nav --}}
</nav>
