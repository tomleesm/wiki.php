@extends('layouts.search')

@section('content')
@foreach ($searchResult as $result)
    <p>
        <a href="{{ route('article.show', ['title' => $result['title']]) }}">{{ $result['title'] }}</a>
    </p>
    <p>{{ $result['content'] }}</p>
@endforeach
@endsection
