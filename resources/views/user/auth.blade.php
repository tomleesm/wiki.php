@extends('layouts.auth')

@section('back-link')
<a class="nav-link" href="{{ route('home') }}">Back to home page</a>
@endsection

@section('content')
    @include('partials.change-role-modal')
    @include('partials.block-modal')
    @include('partials.alert')

    <h3>Authorization</h3>

    <form method="get" action="{{ route('user.auth') }}" class="form-inline">
        <div class="form-group mb-2 mr-2">
            <label class="sr-only" for="search-user">search user</label>
            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Search user" id="search-user" class="form-control mr-2">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>name</th>
                <th>login from</th>
                <th>E-mail</th>
                <th>role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr data-user-id="{{ $user->id }}">
                <td class="align-middle">{{ $user->name }}</td>
                <td class="align-middle">{{ $user->provider }}</td>
                <td class="align-middle">{{ $user->email }}</td>
                <td class="align-middle">
                    <fieldset>
                        @if($user->role->name == 'Administrator' && $onlyOneAdmin)
                        Administrator
                        @else
                        <select class="form-control role option">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"{{ $user->role->name == $role->name ? 'selected' : ''}}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                    </fieldset>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->appends(['keyword' => $keyword])->links() }}

@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap.native/3.0.0/bootstrap-native.min.js" defer></script>
    <script src="{{ mix('js/auth.js')}}" defer></script>
@endsection
