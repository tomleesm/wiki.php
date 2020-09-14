<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $article->title }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/prism.css')}}" type="text/css">
    </head>
    <body class="line-numbers">
    <div class="container">
        <nav class="navbar navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav nav-tabs mr-auto">
                    <li class="nav-item">
                        {{-- request()->routeIs(): 判斷目前的路由名稱是否爲傳入的參數 --}}
                        {{-- 這是爲了自動切換導覽列的 nav-tabes 是否顯示爲 active --}}
                        <a class="nav-link @if (request()->routeIs('article.show')) active @endif" href="{{ route('article.show', [ 'title' => $article->title ]) }}">Read</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('article.edit')) active @endif" href="{{ route('article.edit', [ 'title' => $article->title ]) }}">Edit</a>
                    </li>
                </ul>

            </div>
        </nav>

        @yield('content')

    </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')
</html>
