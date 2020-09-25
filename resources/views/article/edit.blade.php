@extends('layouts.app')

@section('content')
<div class="container">
    <div class="fixed-top">
        <p class="text-right d-none uploading notification"><img src="/img/loading.svg"> uploading...</p>
    </div>
    <div class="row">
        <div class="col-sm edit" draggable="true">
            <h3>{{ $article->title }}</h3>
            <form action="{{ route('article.update', ['title' => $article->title]) }}" method="post">
                @csrf
                @method('put')

                <input type="hidden" name="article[title]" value="{{ $article->title }}">

                {{-- 如果 textarea 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
                <textarea name="article[content]" id="editArticleContent" class="form-control" rows="25">{{ old('article.content', $article->content) }}</textarea>

                <button class="btn btn-primary" dusk="edit-save-button">Save</button>
            </form>
        </div>
        <div class="col-sm preview markdown-body">
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="{{ mix('js/edit.js')}}" defer></script>
@endsection
