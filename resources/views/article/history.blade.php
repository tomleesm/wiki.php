@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="sm-col-8 history diff">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-1" role="tabpanel" aria-labelledby="list-1-list">1</div>
                <div class="tab-pane fade" id="list-2" role="tabpanel" aria-labelledby="list-1-list">2</div>
                <div class="tab-pane fade" id="list-3" role="tabpanel" aria-labelledby="list-2-list">3</div>
                <div class="tab-pane fade" id="list-4" role="tabpanel" aria-labelledby="list-3-list">4</div>
            </div>
        </div>
        <div class="sm-col-4 history list">
            <div class="list-group list-group-flush">
                <a href="#list-1" id="list-1-list" data-toggle="list" role="tab" aria-controls="1" class="list-group-item list-group-item-action active">Tom edited at 07:23:45 PM, 17 Dec 2019</a>
                <a href="#list-2" id="list-2-list" data-toggle="list" role="tab" aria-controls="2" class="list-group-item list-group-item-action">Tom edited at 07:23:45 PM, 17 Dec 2019</a>
                <a href="#list-3" id="list-3-list" data-toggle="list" role="tab" aria-controls="3" class="list-group-item list-group-item-action">Tom edited at 07:23:45 PM, 17 Dec 2019</a>
                <a href="#list-4" id="list-4-list" data-toggle="list" role="tab" aria-controls="4" class="list-group-item list-group-item-action">Tom edited at 07:23:45 PM, 17 Dec 2019</a>
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
