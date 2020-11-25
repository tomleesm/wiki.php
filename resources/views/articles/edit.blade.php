@extends('layouts.editor')

@section('tab-label', 'Edit')

@section('modal-alert')
    {{-- 權限控制確認對話框 --}}
    @include('partials.articles.auth-confirm-modal')
    {{-- 權限控制修改後的訊息列 --}}
    @include('partials.alert')
@endsection

@section('form')
<form action="{{ route('articles.update', ['title' => $article->title]) }}" method="post">
@endsection


@section('method-extra')
{{-- 更新資料，所以method=PUT --}}
@method('put')
@endsection

@section('hidden-article-id')
{{-- 執行權限控制需要抓取 $article->id --}}
<input type="hidden" name="article[id]" value="{{ $article->id }}">
@endsection

@section('textarea-old-value')
{{ old('article.content', $article->content) }}
@endsection


@section('btn-save-cancel')
{{-- 按鈕 Save, Cancel 和權限控制選單 --}}
<div class="form-inline">
    @can('update', $article)
    @parent
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
@endsection
