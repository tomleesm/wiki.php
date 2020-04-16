@extends('layouts.app')

@section('content')
@empty($article->content)
    <p>Welcome to Wiki.</p>
    <p><a href="{{ route('article.edit', ['title' => 'home']) }}">Start to write something.</a></p>
@else

@if($article->title == 'home')
{{ Breadcrumbs::render('home') }}
@else
{{ Breadcrumbs::render('article', $article) }}
@endif

<h3>{{ $article->title }}</h3>

<div class="article content markdown-body">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{{ $article->content }}
</div>
@endempty
@endsection

@section('javascript')
    <script src="{{ asset('js/read.js')}}" defer></script>
@endsection
