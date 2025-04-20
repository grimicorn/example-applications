<!doctype html>
<html {{ language_attributes() }}>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ wp_head() }}
    </head>
    <body {{ body_class() }}>
        <div class="body-bg clearfix">
            {{ do_action('get_header') }}
            @include('layout.header')

            <div class="site-wrap" role="document">
                @yield('content')
            </div> {{-- /.site-wrap --}}

            {{ do_action('get_footer') }}
            @include('layout.footer')
            {{ wp_footer() }}
        </div>
    </body>
</html>
