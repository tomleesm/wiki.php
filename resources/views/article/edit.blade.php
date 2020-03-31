@extends('layouts.app')

@section('content')
<h3>{{ $article->title }}</h3>
<form action="{{ route('article.update', ['title' => $article->title]) }}" method="post">
    @csrf
    @method('put')

    <input type="hidden" name="article[title]" value="{{ $article->title }}">
    <textarea name="article[content]" value="{{ old('article.content') }}"></textarea>

    <button type="submit">Save</button>
</form>
@endsection
