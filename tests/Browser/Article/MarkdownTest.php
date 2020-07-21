<?php

namespace Tests\Browser\Article;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Faker\Factory as Faker;

class MarkdownTest extends DuskTestCase
{
    /**
     * issue#41 顯示條目編輯器：標題、輸入區、預覽
     *
     * @group md1
     */
    public function testShowEditor()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(USer::class)->create();
            $faker = Faker::create();
            $testText = $faker->text;

            // 登入
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSeeLink('Edit')
                    ->clickLink('Edit')
                    // 顯示編輯器標題、輸入區、預覽
                    ->assertSeeIn('.edit h3', 'home')
                    ->assertPresent('.edit #editArticleContent')
                    ->assertPresent('.preview')
                    // 輸入文字，會顯示在預覽
                    ->type('article[content]', $testText)
                    ->assertSeeIn('.preview', $testText);
        });
    }
}
