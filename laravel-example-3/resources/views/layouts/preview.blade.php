<!DOCTYPE html>
<html lang="en" class="h-100 w-100">
<head>
    @include('shared.head')

    <meta name="viewport" content="width=1200">
    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="app-container h-100 w-100">
    @yield('content')
</body>
</html>
