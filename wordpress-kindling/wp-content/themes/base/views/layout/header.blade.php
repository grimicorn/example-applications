<header class="site-header mb-8">
    <div class="container">
        <div class="inner-container">
            @include('layout.logo')
            @include('layout.navigation-toggle')

        </div>
        <div class="navigation-items-wrap js-navigation-menu">
            @include('layout.navigation')
            @include('layout.mobile-nav-items')
        </div>

    </div>
</header>
{{-- <header class="banner navbar navbar-static-top site-header" role="banner">
    <div class="container">
        <div class="navbar-header">
            <div class="site-logo-wrap">
                <a
                class="site-logo-link"
                href="{{ home_url('/') }}"
                >
                    <img
                    src="{{ get_template_directory_uri() }}/assets/images/logo.png    "
                    alt="{{ bloginfo('name') }}"
                    width="150"
                    height="60"
                    class="site-logo img-responsive">
                </a>
            </div>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <nav class="collapse navbar-collapse" role="navigation">
            @if(has_nav_menu('primary_navigation'))
                @php
                wp_nav_menu([
                    'theme_location' => 'primary_navigation',
                    'menu_class' => 'nav navbar-nav navbar-hover',
                    'depth' => 2,
                ]);
                @endphp
            @endif
        </nav>
    </div>
</header> --}}
