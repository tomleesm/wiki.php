<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function auth() {
        $users = User::all();
        $onlyOneAdmin = Role::where('name', 'Administrator')->first()->users()->count() === 1;

        return view('user.auth', [
                    'users' => $users,
                    'onlyOneAdmin' => $onlyOneAdmin,
               ]);
    }
}
