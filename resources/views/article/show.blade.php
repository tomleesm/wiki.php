<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>{{ $article->title }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" type="text/css">
    </head>
    <body>
    <div class="container markdown-body">
        <h3>{{ $article->title }}</h3>

        <div class="article content">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{{ $article->content }}
        </div>
    </div>
    </body>
    <script src="{{ asset('js/app.js') }}" defer></script>
</html>
