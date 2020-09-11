@extends('layouts.app')

@section('content')
@if( empty($article->content) && $article->title == 'home' )
    <p>Welcome to Wiki.</p>
    <p><a href="{{ route('article.edit', ['title' => 'home']) }}">Start to write something.</a></p>
@else

<h3>{{ $article->title }}</h3>
<p>
    <a href="{{ route('article.history', ['title' => $article->title]) }}" class="text-muted">
        <?php $history = $article->revisionHistory->first(); ?>
        <?php
            $history_created_at = new \Carbon\Carbon($history->created_at);
            $history_created_at->tz = 'Asia/Taipei';
        ?>
        Last edit by {{ $history->userResponsible()->name }} at {{ $history_created_at->diffForHumans() }}
    </a>
</p>

<div id="readArticleContent" class="article content markdown-body">
{{-- 如果 $article->content 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{!! $article->content !!}
</div>
@endempty
@endsection

@section('javascript')
    <script src="{{ asset('js/prism.js')}}" defer></script>
@endsection
