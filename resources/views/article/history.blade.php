@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="sm-col-8 history diff">
            <div class="tab-content" id="nav-tabContent">
                @foreach($article->revisionHistory as $history)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="list-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="list-{{ $loop->iteration }}-list">
                    <p>{{ $history->oldValue() ?? '' }}</p>
                    <p>{{ $history->newValue() ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="sm-col-4 history list">
            <div class="list-group list-group-flush">
                @foreach($article->revisionHistory as $history)
                    <a href="#list-{{ $loop->iteration }}" id="list-{{ $loop->iteration }}-list" data-toggle="list" role="tab" aria-controls="{{ $loop->iteration }}" class="list-group-item list-group-item-action @if($loop->first) active @endif">
                    {{ $history->userResponsible()->name }} edited at {{ $history->created_at }}
                </a>
                @endforeach
                <!-- 分頁 Pagination -->
                <div class="list-group-item">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true"><i class="fas fa-caret-left"></i></span>
                                </a>
                            </li>
                            <li class="page-item active">
                                <span class="page-link" href="#">1 <span class="sr-only">(current)</span></span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true"><i class="fas fa-caret-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection
