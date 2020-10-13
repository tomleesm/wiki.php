<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        $this->checkProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $this->checkProvider($provider);
        $oauthUser = Socialite::driver($provider)->user();

        // 抓取之前新增的使用者，沒有的話新增一個
        $user = User::where('oauth_id', $oauthUser->getId())
                    ->where('provider', $provider)
                    ->first();

        if(empty($user)) {
            $user                    = new User();
            $user->name              = $oauthUser->getName();
            $user->email             = $oauthUser->getEmail();
            $user->email_verified_at = now();
            $user->password          = Hash::make($oauthUser->token);
            $user->remember_token    = Str::random(10);
            $user->oauth_id          = $oauthUser->getId();
            $user->provider          = $provider;
            $user->save();
        }

        // Login and "remember" the given user
        Auth::login($user, true);

        return back();

    }

    /**
     * 檢查是否爲開放可用的 OAuth providers
     */
    private function checkProvider($provider) {
        $providers = ['google'];

        if( ! in_array($provider, $providers) ) {
            throw new Exception('wrong provider');
        }
    }
}
