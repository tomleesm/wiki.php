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

        // GitHub 需要 stateless 才不會丟出 InvalidStateException
        // 所以一律使用 stateless()
        $oauthUser = Socialite::driver($provider)->stateless()->user();

        // 抓取之前新增的使用者，沒有的話新增一個
        $user = User::where('oauth_id', $oauthUser->getId())
                    ->where('provider', $provider)
                    ->first();

        if(empty($user)) {
            $user                 = new User();
            $user->name           = $oauthUser->getName();
            $user->oauth_id       = $oauthUser->getId();
            $user->provider       = $provider;
            $user->email          = $oauthUser->getEmail();
            $user->save();
        } else if($oauthUser->getName() != $user->name) {
            // 如果修改了第三方網站的使用者名稱，則 wiki.php 也要跟著改
            $user->name = $oauthUser->getName();
            $user->save();
        }

        // 登入並記住使用者
        Auth::login($user, true);

        return back();
    }

    /**
     * 檢查是否爲開放可用的 OAuth providers
     */
    private function checkProvider($provider) {
        $providers = ['google', 'github'];

        if( ! in_array($provider, $providers) ) {
            abort(403, 'Unauthorized OAuth provider: ' . $provider);
        }
    }
}
