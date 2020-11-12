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

    public function auth(Request $request) {
        $this->authorize('auth', Auth::user());

        $roles = Role::get();
        $onlyOneAdmin = Role::where('name', 'Administrator')->first()->users()->count() === 1;
        $keyword = $request->query('keyword');

        // 搜尋使用者
        if( ! empty($keyword) ) {
            $users = User::where('name',       'LIKE', "%$keyword%")
                         ->orWhere('provider', 'LIKE', "%$keyword%")
                         ->orWhere('email',    'LIKE', "%$keyword%")
                         ->orderBy('name', 'ASC')
                         ->paginate(25);
        } else {
            $users = User::orderBy('name', 'ASC')->paginate(25);
        }

        return view('user.auth', [
                    'users' => $users,
                    'roles' => $roles,
                    'onlyOneAdmin' => $onlyOneAdmin,
                    'keyword' => $keyword,
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
