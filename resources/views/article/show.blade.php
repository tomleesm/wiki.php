<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>{{ $article->title }}</title>
    </head>
    <body>
    <h3>{{ $article->title }}</h3>

    <div class="article content">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{{ $article->content }}
    </div>
    </body>
    <script src="{{ url('/js/app.js') }}"></script>
</html>
