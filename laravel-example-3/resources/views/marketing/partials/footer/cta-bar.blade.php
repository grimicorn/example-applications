@if (isset($footerCTABar['content']))
<div class="bg-color4 fc-color2 hide-print">
    <div class="container footer-cta-bar">
        @guest
            <h2 class="footer-cta-bar-content">
                {{ $footerCTABar['content'] }}
            </h2>

            @if (!empty($footerCTABar['btnLabel']) && !empty($footerCTABar['btnLink']))
            <a
            href="{{ $footerCTABar['btnLink'] }}"
            class="btn btn-color2 btn-ghost">{{ $footerCTABar['btnLabel'] }}</a>
            @endif
        @endguest
    </div>
</div>
@endif
