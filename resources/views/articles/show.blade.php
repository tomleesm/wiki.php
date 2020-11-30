@extends('layouts.app')

@section('content')
<div class="row title">
    <h1>{{ $article->title }}</h1>

    <div class="ml-auto mt-2">
    @if($article->exist)
        <a class="btn btn-success" href="{{ route('articles.edit', [ 'title' => $article->title ]) }}" role="button">Edit</a>
    @else
        <a class="btn btn-success" href="{{ route('articles.create') }}" role="button">Create</a>
    @endif
    </div>
</div>

@if( ! $article->exist && $article->title == 'home' )
    <p>Welcome to Wiki.</p>
    <p><a href="{{ route('articles.create') }}">Start to write something.</a></p>
@else
<div class="row">
    <div class="content markdown-body col-12 @unless($article->toc == '') col-lg-10 @endunless">
{{-- 如果 $article->body 有縮排，會造成 markdown 轉 html 的第一行變成 <pre> --}}
{!! $article->body !!}
    </div>

    @unless($article->toc == '')
    {{-- 寬度 lg 以上才顯示目錄 --}}
    <div class="toc d-none d-lg-block col-lg-2 ml-auto">
{!! $article->toc !!}
    </div>
    @endunless
</div>
@endempty
@endsection

@section('javascript')
    <script src="{{ asset('js/prism.js')}}" defer></script>
@endsection
