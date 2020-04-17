@extends('layouts.search')

@section('content')
    @if($query->result->count() === 0)
        <p>We can't find anything. Please try other keywords.</p>
    @else
        @foreach ($query->result as $result)
        <p>
            <a href="{{ route('article.show', ['title' => $result['title']]) }}">{{ $result['title'] }}</a>
        </p>
        <p>{{ $result['content'] }}</p>
        @endforeach
    @endempty
@endsection
