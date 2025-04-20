<div class="container site-header">
    <div class="navbar-header">
        <div class="site-logo-link-wrap">
            <a
            class="site-logo-link"
            href="{{ url('/') }}">
                <img
                src="{{ url('/img/marketing-logo.png') }}"
                alt="{{ config('app.name') }}"
                width="118"
                height="118"
                class="site-logo">
            </a>
        </div>

        <button type="button" class="navbar-toggle collapsed user-toggle hide-print" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
            <span class="sr-only">Toggle User Menu</span>
            <i class="fa fa-user"></i>
        </button>

        <button type="button" class="navbar-toggle collapsed hide-print" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>  {{-- /.navbar-header --}}

    @include('marketing.partials.header.navigation')
    @include('marketing.partials.header.login-register')
</div>
