<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function changeRole($userId, Request $request) {
        $this->authorize('auth', Auth::user());

        // 修改使用者角色
        DB::transaction(function () use ($userId, $request) {
            $user = User::findOrFail($userId);
            $user->role_id = $request->input('roleId');
            $user->save();
        });

        // 成功修改，回傳成功訊息
        return response()->json([
            'status' => 'success',
            'message' => 'Change role successfully',
        ]);
    }
}
