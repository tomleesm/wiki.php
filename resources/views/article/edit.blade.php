@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm edit">
            <h3>{{ $article->title }}</h3>
            <form action="{{ route('article.update', ['title' => $article->title]) }}" method="post">
                @csrf
                @method('put')

                <input type="hidden" name="article[title]" value="{{ $article->title }}">
                <textarea name="article[content]" value="{{ old('article.content') }}" id="editArticleContent"></textarea>

                <button type="submit">Save</button>
            </form>
        </div>
        <div class="col-sm preview">
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="{{ asset('js/edit.js')}}" defer></script>
@endsection
