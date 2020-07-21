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
}
