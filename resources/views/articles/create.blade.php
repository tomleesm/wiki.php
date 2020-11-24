@extends('layouts.editor')

@section('tab-label', 'Create')

@section('form')
<form method="post" action="{{ route('articles.store') }}">
@endsection
