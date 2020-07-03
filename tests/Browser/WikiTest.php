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
}
