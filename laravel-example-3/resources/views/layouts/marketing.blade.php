<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KHZGJNQ');</script>
    <!-- End Google Tag Manager -->

    <!-- Global site tag (gtag.js) - Google Ads: 837465225 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-837465225"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-837465225');
        </script>
    <!-- Global site tag END -->

    <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '218721282179745');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=218721282179745&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    @section('title')
    {{ config('app.name') }}: {{ $pageTitle }}
    @endsection

    @include('shared.head')

<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="{{ mix('css/marketing.css') }}" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>

    @if(config('app.usersnap_enabled'))
    @include('shared.usersnap')
    @endif
</head>
<body class="{{ isset($bodyClass) ? $bodyClass : '' }}">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KHZGJNQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div id="app" v-cloak>
        @include('marketing.partials.header.header')

        @unless($pageHeaderDisabled)
        @include('marketing.partials.page-header', ['title' => $pageTitle])
        @endunless

        {{--  Alerts  --}}
        <div class="container mt2">
            @include('shared.form-alerts')
        </div>

        <!-- Main Content -->
        <div class="site-content {{ $currentPage }}-content">
            @yield('content')
        </div>

        @include('marketing.partials.footer.footer')

        <confirm></confirm>
        <requires-auth-modal></requires-auth-modal>
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/marketing.js') }}"></script>
    <!-- ConversionRuler Script BEGIN -->
    <script>(function (w, id, q) {var s='script',d=w.document,p=d.getElementsByTagName(s)[0],n=d.createElement(s);
        w._crq=w._crq?w._crq:[];w._crq.push(q);n.src='//www.conversionruler.com/bin/js.php?siteid='+id;p.parentNode.insertBefore(n, p);
        }(window,'7119',0));</script><noscript><div style="position: absolute; left: 0"><img
        src="https://www.conversionruler.com/bin/tracker.php?siteid=7119&amp;nojs=1" alt="" width="1" height="1"
        /></div></noscript>
    <!-- ConversionRuler Script END -->
    <script src="https://my.hellobar.com/8f75aeacd7a18567121af3a2406b04e2cd6bfb84.js" type="text/javascript" charset="utf-8" async="async"></script>
</body>
</html>
