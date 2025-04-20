@if (has_nav_menu('social_media_menu'))

        @php
        wp_nav_menu([
            'theme_location' => 'social_media_menu',
            'menu_class' => 'social-nav-menu',
            'container' => 'nav',
            'container_class' => 'social-nav',
            'walker' => new \Kindling\Support\NavWalkers\PrimaryNavWalker
        ]);
        @endphp

@endif
