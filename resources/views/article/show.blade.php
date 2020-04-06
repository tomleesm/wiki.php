@extends('layouts.app')

@section('content')
<h3>{{ $article->title }}</h3>

<div class="article content markdown-body">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{{ $article->content }}
</div>
@endsection

@section('javascript')
    <script src="{{ asset('js/read.js')}}" defer></script>
@endsection
