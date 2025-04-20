<div class="footer">
    <div class="w-3/4 m-auto py-8 flex justify-center text-sm">
        @if(optional(auth()->user())->isDeveloper() or optional(auth()->user())->isAdmin())
            <a href="{{ config('domain.support_url') }}">Get Support</a>
            <span class="inline-block px-2">|</span>
        @endif
        Copyright &copy;{{ now()->year }}
        <span class="inline-block px-2">|</span>
        Version {{ config('domain.version_number') }}
    </div>
</div>
