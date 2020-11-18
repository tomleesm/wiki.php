@extends('layouts.app')

@section('content')
<h1>{{ $article->title }}</h1>

@if( ! $article->exist && $article->title == 'home' )
    <p>Welcome to Wiki.</p>
    <p><a href="{{ route('articles.create') }}">Start to write something.</a></p>
@else

<div id="readArticleContent" class="article content markdown-body">
    <div id="body">
{{-- 如果 $article->body 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{!! $article->body !!}
    </div>
    <div id="toc">
{!! $article->toc !!}
    </div>
</div>
@endempty
@endsection

@section('javascript')
    <script src="{{ asset('js/prism.js')}}" defer></script>
@endsection
