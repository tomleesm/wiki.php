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
                    ->assertPathIs('/article/home')
                    ->assertSee('Welcome to Tom wiki. Use it to write something.')
                    ->assertSeeLink('Start writing');
        });
    }

    /**
     * 編輯條目後存檔
     *
     * @return void
     */
    public function testEditArticleAndSave()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/article/edit/home')
                    ->assertSee('Home')
                    ->assertPresent('textarea[name="articleContent"]')
                    ->type('articleContent', 'test')
                    ->press('Save')
                    ->assertPathIs('/article/home')
                    ->assertSee('test')
                    ->assertDontSee('Welcome to Tom wiki. Use it to write something.')
                    ->assertDontSeeLink('Start writing');
        });
    }
}
