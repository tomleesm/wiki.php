<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class WikiTest extends DuskTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
    }

    /**
     * 尚未有任何條目時，條目 home 顯示提示訊息
     *
     * @return void
     */
    public function testHomeEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/read/home')
                    ->assertSee('Welcome to Wiki.')
                    ->assertSeeLink('Start to write something.');
        });
    }

    /**
     * 會員登入認證
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {

            // 新增一個使用者
            $user = new User();
            $user->name = 'Tom';
            $user->email = 'tomleesm@gmail.com';
            $password = '123456';
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->save();

            $this->assertDatabaseHas('users', [
                'name' => 'Tom',
                'email' => 'tomleesm@gmail.com'
            ]);

            // 連到首頁，點選 Login
            $browser->visit('/')
                    ->clickLink('Login')
                    ->assertPathIs('/input/username')
                    // 直接點選 Next，會提示要輸入
                    ->press('Next')
                    ->assertSee('Please type E-mail.')
                    // 驗證輸入值爲 E-mail
                    ->type('email', 'vdlnewonewoew')
                    ->press('Next')
                    ->assertSee('Input is not E-mail. Please type again.')
                    // 驗證已經有這個使用者
                    ->type('email', 'notUser@email.com')
                    ->press('Next')
                    ->assertSee('Not registered E-mail.')
                    // 輸入正確的 E-mail
                    ->type('email', $user->email)
                    ->press('Next')
                    ->assertPathIs('/input/password')
                    // 欄位密碼上方顯示 E-mail
                    ->assertSee($user->email)
                    // 密碼不能是空的
                    ->press('Login')
                    ->assertSee('Please type password.')
                    // 輸入錯誤的密碼
                    ->type('password', 'wrong password')
                    ->press('Login')
                    ->assertSee('Not correct password. Please try again.')
                    // 登入成功，跳轉回首頁
                    ->type('password', $password)
                    ->press('Login')
                    ->assertPathIs('/read/home');
        });
    }
}
