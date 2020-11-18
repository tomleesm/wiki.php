@extends('layouts.app')

@section('content')
<div class="container">
    <div class="fixed-top">
        <p class="text-right d-none uploading notification"><img src="/img/loading.svg"> uploading...</p>
    </div>
    <div class="row">
        <div class="col-sm edit">
            <h3>{{ $article->title }}</h3>
            <form method="post" action="{{ route('articles.store') }}">
                @csrf

                @include('layouts.hiddenArticleTitle', ['article' => $article])
                @include('layouts.fileDialog')

                {{-- 如果 textarea 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
                <textarea name="article[content]" id="editArticleContent" class="form-control" draggable="true"></textarea>

                <button class="btn btn-primary">Save</button>
                <a class="btn" href="{{ route('articles.show', ['title' => $article->title]) }}">Cancel</a>
            </form>
        </div>
        <div class="col-sm preview markdown-body">
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="{{ mix('js/edit.js')}}" defer></script>
@endsection
