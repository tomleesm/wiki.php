<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\OnlyOneEmail;
use Illuminate\Validation\ValidationException;

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
            'email' => [
                'required',
                'email',
                // 在表格 users 中已有這個使用者，而且只有一個
                new OnlyOneEmail
            ]
        ], [
            'email.required' => 'Please type E-mail.',
            'email.email' => 'Input is not E-mail. Please type again.',
        ]);

        session(['email' => $request->email]);
        return redirect('/input/password');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            'password.required' => 'Please type password.'
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'password' => [trans('auth.failed')],
        ]);
    }
}
