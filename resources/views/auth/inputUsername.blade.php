<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>輸入 username</title>
    </head>
    <body>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    {{-- 錯誤訊息有 HTML，爲了正確顯示而取消 escape --}}
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ url('/input/username') }}">
            @csrf
            <input type="text" value="{{ old('email') }}" name="email" id="email" placeholder="email">
            <button type="submit">Next</button>
        </form>
    </body>
</html>
