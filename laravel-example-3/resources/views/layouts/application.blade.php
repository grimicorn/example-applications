@php
$disableSidebar = isset($disableSidebar) ? $disableSidebar: false;
@endphp
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


    @include('shared.head')

    <meta name="viewport" content="width=1200">
    <!-- CSS -->
    <link href="/css/introjs.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @if(config('app.usersnap_enabled'))
    @include('shared.usersnap')
    @endif
</head>
<body class="with-navbar app-container">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KHZGJNQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="spark-app" class="relative" v-cloak>
        <overlay-tour-wrap
          data-url="{{ $tourUrl ?? '' }}"
          :data-enabled="{{ (isset($tourEnabled) and $tourEnabled) ? 'true' : 'false' }}"
        >
            <!-- Navigation -->
            @include('app.shell.navbar')

            <div class="container pl0 pr0">
                @if (!$disableSidebar)
                <div class="app-sidebar">
                    @include('app.shell.sidebar')
                </div>
                @endif

                <div class="app-content-wrap {{ $disableSidebar ? 'width-100' : '' }}">
                    @include('app.sections.shared.page-header', [
                        'pageTitle' => isset($pageTitle) ? $pageTitle : '',
                        'pageSubtitle' => isset($pageSubtitle) ? $pageSubtitle : '',
                    ])
                    @yield('before-content')
                    <div class="app-content clearfix">
                        @yield('content')
                    </div>
                    @yield('after-content')
                </div>
            </div>

            <confirm></confirm>
        </overlay-tour-wrap>
    </div>

    <!-- JavaScript -->
    <script src="/js/intro.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- ConversionRuler Script BEGIN -->
        <script>(function (w, id, q) {var s='script',d=w.document,p=d.getElementsByTagName(s)[0],n=d.createElement(s);
        w._crq=w._crq?w._crq:[];w._crq.push(q);n.src='//www.conversionruler.com/bin/js.php?siteid='+id;p.parentNode.insertBefore(n, p);
        }(window,'7119',0));</script><noscript><div style="position: absolute; left: 0"><img
        src="https://www.conversionruler.com/bin/tracker.php?siteid=7119&amp;nojs=1" alt="" width="1" height="1"
        /></div></noscript>
    <!-- ConversionRuler Script END -->
</body>
</html>
