<!DOCTYPE html>
<html lang="en">
<head>
    @include('shared.head')
    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="with-navbar">
    <div id="spark-app" v-cloak>
        <!-- Navigation -->
        @include('spark::nav.user')

        <div class="container">
            <div class="app-sidebar">
                @include('app.partials.sidebar')
            </div>

            <div class="app-content">
                @yield('content')
            </div>
        </div>

        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @endif
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
