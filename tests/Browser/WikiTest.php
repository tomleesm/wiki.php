<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WikiTest extends DuskTestCase
{
    /**
     * 尚未有任何條目時，條目 home 顯示提示訊息
     *
     * @return void
     */
    public function testHomeEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/article/home')
                    ->assertSee('Welcome to Wiki.')
                    ->assertSeeLink('Start to write something.');
        });
    }

    /**
     * 測試登入
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {

            // 新增一個使用者
            $user = new User();
            $user->name = 'tomleesm@gmail.com';
            $user->email = 'tomleesm@gmail.com';
            $password = '123456';
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->save();

            $browser->visit('/')
                    ->clickLink('Sign in')
                    ->assertPathIs('/input/username')
                    // 直接按下 Next，應該顯示錯誤訊息
                    ->press('Next')
                    ->assertPathIs('/input/username')
                    ->assertSee('Please type E-mail.')
                    // 驗證輸入值爲 E-mail
                    ->type('email', 'vdlnewonewoew')
                    ->press('Next')
                    ->assertSee('Input is not E-mail. Please type again.')
                    // 驗證已經有這個使用者
                    ->type('email', 'notUser@email.com')
                    ->press('Next')
                    ->assertSee('Not registered E-mail.')->assertSeeLink('Create account ?')
                    // 輸入正確的 E-mail
                    ->type('email', $user->email)
                    ->press('Next')
                    // 顯示輸入密碼表單
                    ->assertPathIs('/input/password')
                    // 欄位密碼上方顯示 E-mail
                    ->assertSee($user->email)
                    // 密碼不能是空的
                    ->press('Next')
                    ->assertSee('Please type password.')
                    // 輸入錯誤的密碼
                    ->type('password', 'wrong password')
                    ->press('Next')
                    ->assertSee('Not correct password. Please try again.')
                    // 登入成功，跳轉回首頁
                    ->type('password', $password)
                    ->press('Next')
                    ->assertPathIs('/article/home');
        });
    }
}
