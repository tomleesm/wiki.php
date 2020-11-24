@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 上傳進度 --}}
    <div class="fixed-top">
        <p class="text-right d-none uploading notification"><img src="/img/loading.svg"> uploading...</p>
    </div>

    @yield('modal-alert', '')

    <div class="row">
        {{-- 輸入 div --}}
        <div class="col-sm edit">
            {{-- 條目標題 --}}
            <h3>{{ $article->title }}</h3>
            @yield('form')
                @csrf
                @yield('method-extra', '')

                {{-- 用隱藏欄位傳送條目標題，在 ArticleController@update 尋找 model Article --}}
                @include('layouts.hiddenArticleTitle', ['article' => $article])
                {{-- 工具列用來觸發檔案選擇的 <input type="file"> --}}
                @include('layouts.fileDialog')

                {{-- 如果 textarea 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
                <textarea name="article[content]" id="editArticleContent" class="form-control" draggable="true">@yield('textarea-old-value', '')</textarea>

                @section('btn-save-cancel')
                <button class="btn btn-primary">Save</button>
                <a class="btn" href="{{ route('articles.show', ['title' => $article->title]) }}">Cancel</a>
                @show
            </form>
        </div>
        {{-- 預覽 div --}}
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
