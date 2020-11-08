@extends('layouts.auth')

@section('content')
    <h3>Permission</h3>
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
                <option value="blocked_users">Blocked users</option>
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
                <th>block</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="align-middle">A</td>
                <td class="align-middle">google</td>
                <td class="align-middle">a@email.com</td>
                <td class="align-middle">
                    <fieldset>
                    Administrator
                    </fieldset>
                </td>
                <td class="align-middle">
                </td>
            </tr>
            <tr>
                <td class="align-middle">B</td>
                <td class="align-middle">google</td>
                <td class="align-middle">b@email.com</td>
                <td class="align-middle">
                    <fieldset>
                        <select class="form-control">
                            <option value="login_user">Login user</option>
                            <option value="editor" selected>Editor</option>
                            <option value="administrator">Administrator</option>
                        </select>
                    </fieldset>
                </td>
                <td class="align-middle">
                    <button class="btn btn-danger" type="button">Block</button>
                </td>
            </tr>
            <tr>
                <td class="align-middle">C</td>
                <td class="align-middle">google</td>
                <td class="align-middle">c@email.com</td>
                <td class="align-middle">
                    <fieldset>
                        <select class="form-control">
                            <option value="login_user" selected>Login user</option>
                            <option value="editor">Editor</option>
                            <option value="administrator">Administrator</option>
                        </select>
                    </fieldset>
                </td>
                <td class="align-middle">
                    <button class="btn btn-danger" type="button">Block</button>
                </td>
            </tr>
            <tr>
                <td class="align-middle">D</td>
                <td class="align-middle">google</td>
                <td class="align-middle">d@email.com</td>
                <td class="align-middle">
                    <fieldset disabled>
                        <select class="form-control">
                            <option value="login_user">Login user</option>
                            <option value="editor">Editor</option>
                            <option value="administrator">Administrator</option>
                        </select>
                    </fieldset>
                </td>
                <td class="align-middle">
                    <button class="btn btn-success" type="button">Unblock</button>
                </td>
            </tr>
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
