<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function auth() {
        $this->authorize('auth', Auth::user());

        $users = User::all();
        $roles = Role::all();
        $onlyOneAdmin = Role::where('name', 'Administrator')->first()->users()->count() === 1;

        return view('user.auth', [
                    'users' => $users,
                    'roles' => $roles,
                    'onlyOneAdmin' => $onlyOneAdmin,
               ]);
    }
}
