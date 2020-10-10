@extends('layouts.app')

@section('content')
@if( empty($article->content) && $article->title == 'home' )
    <p>Welcome to Wiki.</p>
    <p><a href="{{ route('article.edit', ['title' => 'home']) }}">Start to write something.</a></p>
@else

<h1>{{ $article->title }}</h1>

<div id="readArticleContent" class="article content markdown-body">
    <div id="body">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{!! $article->content !!}
    </div>
</div>
@endempty
@endsection

@section('javascript')
    <script src="{{ asset('js/prism.js')}}" defer></script>
@endsection
