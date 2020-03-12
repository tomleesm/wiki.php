<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WikiTest extends DuskTestCase
{
    /**
     * 首頁會跳轉到 /article/read/home 並提示訊息
     *
     * @return void
     */
    public function testHomePageInfo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/article/read/home')
                    ->assertSee('Welcome to Tom wiki. Use it to write something.')
                    ->assertSeeLink('Start writing');
        });
    }
}
