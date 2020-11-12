@extends('layouts.auth')

@section('back-link')
<a class="nav-link" href="{{ url()->previous() }}">Back to previous page</a>
@endsection

@section('content')
<div class="row">
    <div class="col-0 col-lg-4"></div>
    <div class="col-12 col-lg-4 border border-secondary rounded">
        <p class="text-center mt-3">
          <a class="btn btn-outline-primary btn-lg btn-block" role="button" href="/login/google">
             Login with Google
          </a>
        </p>
        <p class="text-center">
          <a class="btn btn-outline-secondary btn-lg btn-block" role="button" href="/login/github">
             Login with GitHub
          </a>
        </p>
    </div>
    <div class="col-0 col-lg-4"></div>
</div>
@endsection
