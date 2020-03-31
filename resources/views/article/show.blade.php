<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>{{ $article->title }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">
    </head>
    <body>
    <div class="container">
        <nav class="navbar navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav nav-tabs mr-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('article.show', [ 'title' => Str::slug($article->title) ]) }}">Read</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('article.edit', [ 'title' => Str::slug($article->title) ]) }}">Edit</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/input/username')}}">Sign in</a>
                    </li>
                </ul>
            </div>
        </nav>

        <h3>{{ $article->title }}</h3>

        <div class="article content markdown-body">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{{ $article->content }}
        </div>
    </div>
    </body>
    <script src="{{ asset('js/app.js') }}" defer></script>
</html>
