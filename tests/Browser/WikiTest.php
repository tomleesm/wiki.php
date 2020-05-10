<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WikiTest extends DuskTestCase
{
    use RefreshDatabase;

    /**
     * 尚未有任何條目時，條目 home 顯示提示訊息
     *
     * @return void
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

}
