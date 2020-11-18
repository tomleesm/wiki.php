@extends('layouts.app')

@section('content')
<div class="container">
    <div class="fixed-top">
        <p class="text-right d-none uploading notification"><img src="/img/loading.svg"> uploading...</p>
    </div>

    @include('partials.articles.auth-confirm-modal')
    @include('partials.alert')

    <div class="row">
        <div class="col-sm edit">
            <h3>{{ $article->title }}</h3>
            <form action="{{ route('articles.update', ['title' => $article->title]) }}" method="post">
                @csrf
                @method('put')

                <input type="hidden" name="article[id]" value="{{ $article->id }}">

                @include('layouts.hiddenArticleTitle', ['article' => $article])
                @include('layouts.fileDialog')

                {{-- 如果 textarea 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
                <textarea name="article[content]" id="editArticleContent" class="form-control" draggable="true">{{ old('article.content', $article->content) }}</textarea>

                <div class="form-inline">
                    @can('update', $article)
                    <button class="btn btn-primary">Save</button>
                    <a class="btn" href="{{ route('articles.show', ['title' => $article->title]) }}">Cancel</a>
                    @else
                    <a href="{{ route('articles.show', ['title' => $article->title]) }}">back to {{ $article->title }}</a>
                    @endcan

                    @auth
                    @if(Auth::user()->role->name == 'Administrator')
                    <div class="form-group ml-auto">
                        <select class="form-control" id="article-auth">
                            @foreach($options as $option)
                                <option value="{{ $option['id'] }}"{{ $option['selected'] ?? '' }}>
                                {{ $option['name'] }}
                            </option>
                            @endforeach
                        </select>
                        <label for="article-auth" class="ml-2">can update</label>
                    </div>
                    @endif
                    @endauth
                </div>
            </form>
        </div>
        <div class="col-sm preview markdown-body">
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap.native/3.0.0/bootstrap-native.min.js" defer></script>
    <script src="{{ mix('js/edit.js')}}" defer></script>
@endsection
