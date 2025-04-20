<!-- Meta Information -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>@yield('title', config('app.name'))</title>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" href="/favicon.png?v=1" />

<!-- Scripts -->
@yield('scripts', '')

<!-- Global Spark Object -->
<script>
    window.Spark = <?php echo json_encode((new \App\Support\HeadVariables)->get($errors)); ?>;
</script>
