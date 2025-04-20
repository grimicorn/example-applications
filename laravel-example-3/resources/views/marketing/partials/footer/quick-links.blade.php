<nav class="footer-quick-links-navigation">
    <ul class="footer-quick-links list-unstyled footer-section-list">
        @foreach($footerQuickLinks as $link)
        <li>
            <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
        </li>
        @endforeach
    </ul> {{-- /.navbar-nav --}}
</nav>
