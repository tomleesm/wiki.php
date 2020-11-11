@extends('layouts.auth')

@section('content')
    @include('partials.change-role-modal')
    @include('partials.block-modal')
    @include('partials.alert')

    <h3>Authorization</h3>
    <div class="form-inline">
        <div class="form-group mb-2 mr-2">
            <label class="sr-only" for="search-user">search user</label>
            <input type="text" name="search_user" placeholder="Search User" id="search-user" class="form-control mr-2">
            <button class="btn btn-primary">Search</button>
        </div>

        <div class="form-group ml-auto mb-2">
            <label for="filter-users" class="mr-2">show:</label>
            <select class="form-control" id="filter-users">
                <option value="all_users" selected>All users</option>
                <option value="editors">Editors</option>
                <option value="administrators">Administrators</option>
            </select>
        </div>
    </div>

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

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">1</a></li>
            <li class="page-item active" aria-current="page">
                <span class="page-link">
                2
                <span class="sr-only">(current)</span>
                </span>
            </li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap.native/3.0.0/bootstrap-native.min.js" defer></script>
    <script src="{{ mix('js/auth.js')}}" defer></script>
@endsection
