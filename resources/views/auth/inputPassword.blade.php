<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <title>輸入密碼</title>
    </head>
    <body>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('login') }}" method="post" accept-charset="utf-8">
            @csrf
            <label>{{ session('email') }}</label>
            <input type="hidden" value="{{ session('email') }}" name="email" id="email"/>
            <input type="password" value="" name="password" id="password"/>
            <button>Next</button>
        </form>
    </body>
</html>
