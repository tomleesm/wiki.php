<?php

namespace Tests\Browser\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
}
