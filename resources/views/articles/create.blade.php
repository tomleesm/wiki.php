@extends('layouts.editor')

@section('form')
<form method="post" action="{{ route('articles.store') }}">
@endsection
