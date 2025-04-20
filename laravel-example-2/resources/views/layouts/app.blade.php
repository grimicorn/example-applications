<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-screen antialiased leading-none {{ $body_class ?? '' }}">
    <div id="app" class="h-full w-full">
        @if($display_header ?? true)
            @include('partials.header')
        @endif

        <alerts></alerts>

        @yield('content')
        <portal-target name="panel" multiple></portal-target>
        @include('partials.footer')
    </div>
    <!-- Scripts -->
    @routes
    {{-- Edit JS -> PHP in \App\Providers\ViewProvider --}}
    <script>
        window.vms = {
            machines: '{!! $machines->toJson() !!}',
            jobStatuses: '{!! $job_statuses->toJson() !!}',
            pickStatuses: '{!! $pick_statuses->toJson() !!}',
            artStatuses: '{!! $art_statuses->toJson() !!}',
            jobFlags: '{!! $job_flags->toJson() !!}',
            wipStatuses: '{!! $wip_statuses->toJson() !!}',
        };
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
