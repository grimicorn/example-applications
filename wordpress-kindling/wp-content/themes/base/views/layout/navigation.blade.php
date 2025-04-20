@if (has_nav_menu('primary_navigation'))

        @php
        wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'menu_class' => 'site-navigation-menu',
            'container' => 'nav',
            'container_class' => 'site-navigation',
            'walker' => new \Kindling\Support\NavWalkers\PrimaryNavWalker
        ]);
        @endphp

@endif
