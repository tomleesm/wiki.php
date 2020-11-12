<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Login - {{ env('APP_NAME', 'wiki.php') }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">
    </head>
    <body>
    <div class="container">
        <nav class="navbar navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav mr-auto">
                    <li class="nav-item">
                      @yield('back-link')
                    </li>
                </ul>
            </div>
        </nav>

        @yield('content')

    </div>
    </body>
    @yield('javascript')
</html>
