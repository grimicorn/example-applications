<div class="container app-navbar">
    <div class="app-navbar-left">
        <a class="app-navbar-logo-link" href="{{ route('home') }}">
            <img
            src="/img/application-logo.png"
            class="app-navbar-logo"
            width="40"
            height="40"
            >
        </a>
    </div>

    <div class="app-navbar-right">

        @if(!is_null(session('spark:impersonator')))
        <a
        href="/spark/kiosk/users/stop-impersonating/"
        class="btn btn-small mr2">Stop User Impersonation</a>
        @endisset

        <div class="app-navbar-icons">
            <a
            href="{{ url('contact') }}"
            id="overlay_tour_contact_link"
            class="app-navbar-icon fa fa-question-circle-o"
            target="_blank">
                <span class="sr-only">Contact Us</span>
            </a>
        </div>

        @isset($navbarMenuItems)
        <div id="overlay_tour_step_5">
            <app-navbar-dropdown
            :menu-items="{{ json_encode($navbarMenuItems) }}"></app-navbar-dropdown>
        </div>
        @endisset

        <a
        href="{{ route('profile.edit') }}"
        class="app-navbar-avatar-link flex">
            <user-avatar
            data-loading-height="44"
            data-loading-width="44"
            data-image-class="app-navbar-avatar rounded"></user-avatar>
        </a>
    </div>
</div>
