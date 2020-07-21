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
     * 必須登入才能使用的網址，登入成功後跳轉回該網址
     *
     * @group login3
     *
     */
    public function testRedirectRequireAuth()
    {
         $this->browse(function (Browser $browser) {
             $user = factory(User::class)->create();

             // 連到網址 /edit/test
             $browser->visit('/edit/test')
                     // 應該跳轉到登入頁面
                     ->assertPathIs('/input/username')
                     ->type('email', $user->email)
                     ->press('Next')
                     ->assertSee($user->email)
                     ->assertPathIs('/input/password')
                     ->type('password', 'password')
                     ->press('Login')
                     ->assertPathIs('/edit/test');
         });
    }
}
