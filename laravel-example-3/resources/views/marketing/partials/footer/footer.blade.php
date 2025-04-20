@include('marketing.partials.footer.cta-bar')
<div class="container site-footer">
    <div class="footer-logo-wrap">
        <a
        class="footer-logo-link"
        href="{{ url('/') }}">
            <img
            src="{{ url('/img/marketing-footer-logo.png') }}"
            alt="{{ config('app.name') }}"
            width="422"
            height="113"
            class="footer-logo img-responsive">
        </a>
    </div>

    <div class="footer-quick-links-wrap footer-section hide-print">
        <div class="row">
            <div class="col-sm-10 print-83">
                @include('marketing.partials.footer.quick-links')
            </div>
            <div class="col-sm-2 print-16">
                @include('marketing.partials.social-menu')
            </div>
        </div>
    </div>

</div>
@include('marketing.partials.footer.legal')
