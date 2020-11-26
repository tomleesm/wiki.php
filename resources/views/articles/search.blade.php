@extends('layouts.app')

@section('title', 'Search ' . $keyword)

@section('content')
    @foreach($articles as $article)
    <dl>
        <dt><a href="{{ route('articles.show', ['title' => $article->title]) }}">{{ $article->title }}</a></dt>
        <dd>{{ $article->title }}</dd>
    </dl>
    @endforeach
@endsection
