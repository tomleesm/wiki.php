<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $article->title }} - {{ env('APP_NAME', 'wiki.php') }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/prism.css')}}" type="text/css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    </head>
    <body class="line-numbers">
    <div class="container">
        <nav class="navbar navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav nav-tabs mr-auto">
                    <li class="nav-item">
                        {{-- request()->routeIs(): 判斷目前的路由名稱是否爲傳入的參數 --}}
                        {{-- 這是爲了自動切換導覽列的 nav-tabes 是否顯示爲 active --}}
                        <a class="nav-link @if (request()->routeIs('articles.show')) active @endif" href="{{ route('articles.show', [ 'title' => $article->title ]) }}">Read</a>
                    </li>
                    <li class="nav-item">
                    @if($article->exist)
                        <a class="nav-link @if (request()->routeIs('articles.edit')) active @endif" href="{{ route('articles.edit', [ 'title' => $article->title ]) }}">Edit</a>
                    @else
                        <a class="nav-link @if (request()->routeIs('articles.create')) active @endif" href="{{ route('articles.create') }}">Create</a>
                    @endif
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

            </div>
        </nav>

        @yield('content')

    </div>
    </body>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')
</html>
