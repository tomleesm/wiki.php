<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // 設定跳轉回之前記住的 url
        $this->redirectTo = session('backUrl');
    }

    /**
     * show form to fill username
     *
     * @return void
     */
    public function showInputUsernameForm()
    {
        return view('auth.inputUsername');
    }

    /**
     * show form to fill password
     *
     * @return void
     */
    public function showInputPasswordForm()
    {
        return view('auth.inputPassword');
    }

    /**
     * validate username
     *
     * @return void
     */
    public function validateUsername(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email',
            // 在表格 users 中已有這個使用者，而且只有一個
            Rule::exists('users')->where(function($query) use ($request) {
                $query->where('email', $request->email)->count() === 1;
            })]
        ]);

        session(['email' => $request->email]);
        return redirect('/input/password');
    }
}
