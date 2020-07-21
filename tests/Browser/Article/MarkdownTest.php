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
            $user = factory(User::class)->create();
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

    /**
     *
     * issue#47 標題 h1 到 h6
     *
     * @group md2
     *
     */
    public function testH1H6()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create();

            $browser->loginAs($user)
                    ->visit('/edit/home')
                    // 輸入 # header 1 到 ###### header 6
                    ->type('article[content]', '# header 1')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '## header 2')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '### header 3')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '#### header 4')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '##### header 5')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '###### header 6')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '# 測試')
                    // 顯示預覽 header 1 到 header 6
                    ->assertSourceHas('<h1 id="header-1">header 1</h1>')
                    ->assertSourceHas('<h2 id="header-2">header 2</h2>')
                    ->assertSourceHas('<h3 id="header-3">header 3</h3>')
                    ->assertSourceHas('<h4 id="header-4">header 4</h4>')
                    ->assertSourceHas('<h5 id="header-5">header 5</h5>')
                    ->assertSourceHas('<h6 id="header-6">header 6</h6>')
                    ->assertSourceHas('<h1 id="%E6%B8%AC%E8%A9%A6">測試</h1>')
                    ->click('@edit-save-button')
                    // 儲存後顯示相同的 header 1 到 header 6
                    ->assertSourceHas('<h1>header 1</h1>')
                    ->assertSourceHas('<h2>header 2</h2>')
                    ->assertSourceHas('<h3>header 3</h3>')
                    ->assertSourceHas('<h4>header 4</h4>')
                    ->assertSourceHas('<h5>header 5</h5>')
                    ->assertSourceHas('<h6>header 6</h6>')
                    ->assertSourceHas('<h1>測試</h1>');
        });
    }

    /**
     *
     * issue#48 水平線
     *
     * @group md3
     *
     */
    public function testHorizontalLine()
    {
        $this->browse(function (Browser $browser) {
            // 顯示編輯器
            $user = factory(User::class)->create();

            $browser->loginAs($user)
                    ->visit('/edit/home')
                    // 輸入 ---, ___ 和 ***
                    ->type('article[content]', '___')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '---')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '***')
                    // 應該顯示三個 <hr>
                    ->assertSourceHas("<hr>\n<hr>\n<hr>")
                    ->click('@edit-save-button')
                    // 儲存後顯示相同的 <hr>
                    ->assertSourceHas("<hr>\n<hr>\n<hr>");
        });
    }
}
