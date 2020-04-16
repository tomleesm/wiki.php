<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Search result</title>
    <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">

</head>
<body>
    <div id="app">

        <div class="container">
            <nav class="navbar navbar-light bg-light">
                <a class="navbar-brand" href="{{ route('home') }}">Wiki</a>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
