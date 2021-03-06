@extends('layouts.app')

@section('title', 'Search ' . $keyword)

@section('content')
@inject('markdown', 'App\Markdown\MarkdownService')
@php use Illuminate\Support\Str; @endphp

@if($articles->count() == 0)
    <p>The article <a href="{{ route('articles.show', ['title' => $keyword]) }}">{{ $keyword }}</a> does not exist. You can create it.</p>
    <p>There were no results matching the query.</p>
    </p>
@else
    @foreach($articles as $article)
    <dl>
        <dt><a href="{{ route('articles.show', ['title' => $article->title]) }}" class="h4">{{ $article->title }}</a></dt>
        {{-- 用@inject 注入 MarkdownService，以使用 toHTML() 把 markdown 轉成 html --}}
        {{-- strip_tags() 把 html 轉成純文字 --}}
        {{-- Str::words() 只顯示最多 100 個字，超過則加上... --}}
        <dd>{!! Str::words(strip_tags($markdown->toHTML($article->content)), '100', '...') !!}</dd>
    </dl>
    @endforeach

    {{ $articles->appends(['keyword' => $keyword])->links() }}
@endif
@endsection
