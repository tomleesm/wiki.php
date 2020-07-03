<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WikiTest extends DuskTestCase
{

    use RefreshDatabase;
    /**
     * 尚未有任何條目時，條目 home 顯示提示訊息
     *
     * @group homePage
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
     * 顯示註冊連結和頁面
     *
     * @group registration
     */
    public function testShowRegistrationLinkAndPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSeeLink('Register')
                    ->clickLink('Register')
                    ->assertPathIs('/register');
        });
    }

    /**
     * 正常註冊流程
     *
     * @group registration
     */
    public function testNormalRegistration()
    {
        $this->browse(function (Browser $browser) {
            $newUser = factory(User::class)->make();
            // factory 密碼是已經用 Bcrypt 處理過的，所以覆寫它
            $newUser->password = Str::random(10);

            $browser->visit('/')
                    ->clickLink('Register')
                    // 輸入註冊資料
                    ->type('name', $newUser->name)
                    ->type('email', $newUser->email)
                    ->type('password', $newUser->password)
                    ->type('password_confirmation', $newUser->password)
                    ->press('Register')
                    // 回到首頁
                    ->assertPathIs('/read/home');
                    // 檢查真的註冊成功
                    $user = User::find(1);
                    $this->assertEquals($newUser->name, $user->name);
                    $this->assertEquals($newUser->email, $user->email);
                    $this->assertTrue(Hash::check($newUser->password, $user->password));
        });
    }
}
