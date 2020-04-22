@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="sm-col-8 history diff">
            <div class="tab-content" id="nav-tabContent">
                @foreach($article->revisionHistory as $history)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="list-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="list-{{ $loop->iteration }}-list">
                        <pre>
                        <?php $differ = new \SebastianBergmann\Diff\Differ; ?>
                        {{ $differ->diff( $history->oldValue(), $history->newValue() ) }}
                        </pre>
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
                            <?php use Illuminate\Pagination\LengthAwarePaginator; ?>
                            <?php $paginator = new LengthAwarePaginator($article->revisionHistory,
                                                                        $article->revisionHistory->count(),
                                                                        1);
                            ?>
                            {{ $paginator->links('vendor.pagination.default') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection
