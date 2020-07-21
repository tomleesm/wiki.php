<?php

namespace Tests\Browser\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class LoginTest extends DuskTestCase
{
    /**
     *
     * 顯示登入連結和頁面
     *
     * @group login1
     *
     */
    public function testLoginLinkAndPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSeeLink('Login')
                    ->clickLink('Login')
                    ->assertPathIs('/input/username');
        });
    }

    /**
     *
     * 正常登入流程
     *
     * @group login2
     *
     */
     public function testNormalLogin()
     {
         $this->browse(function (Browser $browser) {
             $user = factory(User::class)->create();

             $browser->visit('/input/username')
                     ->type('email', $user->email)
                     ->press('Next')
                     ->assertSee($user->email)
                     ->assertPathIs('/input/password')
                     ->type('password', 'password')
                     ->press('Login')
                     // 應該回到首頁，右上方顯示 $user->name
                     ->assertPathIs('/read/home')
                     ->assertSee($user->name);
         });
     }


    /**
     *
     * 登出
     *
     * @group login3
     *
     */
     public function testLogout()
     {
         $this->browse(function (Browser $browser) {
             $user = factory(User::class)->create();

             // 登入後右上角應該顯示使用者名稱
             $browser->loginAs($user)->visit('/')
                     ->assertSee($user->name)
                     // 點選連結 Logout 後，顯示連結 Login 和 Register
                     ->clickLink($user->name)
                     ->clickLink('Logout')
                     ->assertSeeLink('Login')
                     ->assertSeeLink('Register');
         });
     }
}
