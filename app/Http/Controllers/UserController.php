<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function auth() {
        $users = User::all();

        return view('user.auth', [
                    'users' => $users,
               ]);
    }
}
