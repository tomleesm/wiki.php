<?php

namespace Tests\Browser\Auth;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\Setting;
use App\Enums\AvailableSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTest extends DuskTestCase
{
    /**
     * 顯示註冊連結和頁面
     *
     * @group r1
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
     * @group r2
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
                    ->press('Register');

            // 檢查真的註冊成功
            $user = User::find(1);
            $this->assertEquals($newUser->name, $user->name);
            $this->assertEquals($newUser->email, $user->email);
            $this->assertTrue(Hash::check($newUser->password, $user->password));

            // 登出，確保不會影響其他測試執行
            $browser->clickLink($newUser->name)->clickLink('Logout');
        });
    }

    /**
     * 異常註冊流程：密碼小於8個字元
     *
     * @group r3
     */
    public function testPasswordLessThanEightChar()
    {
        $this->browse(function (Browser $browser) {
            $newUser = factory(User::class)->make();
            // factory 密碼是已經用 Bcrypt 處理過的，所以覆寫它
            $newUser->password = Str::random(7);

            $browser->visit('/')
                    ->clickLink('Register')
                    // 輸入註冊資料
                    ->type('name', $newUser->name)
                    ->type('email', $newUser->email)
                    ->type('password', $newUser->password)
                    ->type('password_confirmation', $newUser->password)
                    ->press('Register')
                    ->assertSee('The password must be at least 8 characters.');
        });
    }

    /**
     * 異常註冊流程：E-mail 已經有人註冊了
     *
     * @group r4
     */
    public function testEmailHasBeenRegistered()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create();

            $browser->visit('/register')
                    // 輸入註冊資料
                    ->type('name', $user->name)
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('Register')
                    ->assertSee('The email has already been taken.');
        });
    }

    /**
     * 異常註冊流程：密碼和密碼確認欄位輸入不一樣
     *
     * @group r5
     */
    public function testFieldPasswordAndConfirmationDifferent()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->make();

            $browser->visit('/register')
                    // 輸入註冊資料
                    ->type('name', $user->name)
                    ->type('email', $user->email)
                    ->type('password', '12345678')
                    ->type('password_confirmation', '87654321')
                    ->press('Register')
                    ->assertSee('The password confirmation does not match.');
        });
    }

    /**
     * 註冊後需要確認 E-mail 可用
     *
     * @group r6
     */
    public function testConfirmEmailAfterRegister()
    {
        // 設定「註冊後需要確認 E-mail 可用」
        Setting::set(AvailableSetting::confirmEmailAfterRegister(), true);

        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->make();
            $user->password = Str::random(10);

            $browser->visit('/register')
                    // 輸入註冊資料
                    ->type('name', $user->name)
                    ->type('email', $user->email)
                    ->type('password', $user->password)
                    ->type('password_confirmation', $user->password)
                    ->press('Register');

            // 用 Mock 寄出確認信
            // assertSee 請到 $user->email 收信，點擊「確認」按鈕
            // 正常登入後，顯示上一步的「確認 E-mail 可用提示訊息」
            // 確認 E-mail 可用
            // 確認顯示訊息 E-mail $user->email 已確認，確認有連結 Login
            // click link Login
            // 正常登入後，應該回到首頁，右上方顯示 $user->name
        });
    }
}
