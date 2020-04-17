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
            <nav class="navbar navbar-expand-md">
                <a class="navbar-brand" href="{{ route('home') }}">Wiki</a>

                <ul class="navbar-nav nav">
                    <li class="nav-item">
                        <form action="{{ route('article.search') }}" method="get" name="search">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" value="{{ $query->keyword ?? '' }}" placeholder="search" aria-label="search" aria-describedby="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">
                                        <span><i class="fas fa-search"></i></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
</body>
</html>
