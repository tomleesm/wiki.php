<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
            $browser->visit('/')
                    ->clickLink('Sign in')
                    ->assertPathIs('/input/username')
                    // 直接按下 Next，應該顯示錯誤訊息
                    ->press('Next')
                    ->assertPathIs('/input/username')
                    ->assertSee('Please type E-mail.');
        });
    }
}
