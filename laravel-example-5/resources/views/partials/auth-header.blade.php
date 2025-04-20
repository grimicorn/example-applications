<nav class="flex items-center justify-center h-12 px-4 absolute pin-l pin-r">
    <div class="flex-1 text-right">
        <a
            class="mr-6 button-link text-sm text-sm p-0"
            href="{{ url('/login') }}"
        >Login</a>

        @if(config('srcwatch.registration_enabled'))
            <a
                class="button-link text-sm text-sm p-0"
                href="{{ url('/register') }}"
            >Register</a>
        @endif
    </div>
</nav>
