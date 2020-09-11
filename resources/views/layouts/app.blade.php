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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('article.pdf', [ 'title' => $article->title ]) }}">PDF</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('article.history')) active @endif" href="{{ route('article.history', [ 'title' => $article->title ]) }}">History</a>
                    </li>
                </ul>

                <!--  SEARCH -->
                <ul class="navbar-nav nav ml-auto">
                    <li class="nav-item">
                        <form action="{{ route('article.search') }}" method="get" name="search">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="search" aria-label="search" aria-describedby="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">
                                        <span><i class="fas fa-search"></i></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>

                <ul class="navbar-nav nav">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('signin') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')
</html>
