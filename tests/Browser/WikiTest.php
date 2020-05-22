<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class WikiTest extends DuskTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
    }

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

    /**
     * 會員登入認證
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {

            $user = $this->createOneUser();

            // 連到首頁，點選 Login
            $browser->visit('/')
                    ->clickLink('Login')
                    ->assertPathIs('/input/username')
                    // 直接點選 Next，會提示要輸入
                    ->press('Next')
                    ->assertSee('Please type E-mail.')
                    // 驗證輸入值爲 E-mail
                    ->type('email', 'vdlnewonewoew')
                    ->press('Next')
                    ->assertSee('Input is not E-mail. Please type again.')
                    // 驗證已經有這個使用者
                    ->type('email', 'notUser@email.com')
                    ->press('Next')
                    ->assertSee('Not registered E-mail.')
                    // 輸入正確的 E-mail
                    ->type('email', $user->email)
                    ->press('Next')
                    ->assertPathIs('/input/password')
                    // 欄位密碼上方顯示 E-mail
                    ->assertSee($user->email)
                    // 密碼不能是空的
                    ->press('Login')
                    ->assertSee('Please type password.')
                    // 輸入錯誤的密碼
                    ->type('password', 'wrong password')
                    ->press('Login')
                    ->assertSee('Not correct password. Please try again.')
                    // 登入成功，跳轉回首頁
                    ->type('password', $user->password_not_hashed)
                    ->press('Login')
                    ->assertPathIs('/read/home');
        });
    }

    private function createOneUser() {
        // 新增一個使用者
        $user = new User();
        $user->name = 'Tom';
        $user->email = 'tomleesm@gmail.com';
        $password = '123456';
        $user->password = Hash::make($password);
        $user->email_verified_at = now();
        $user->remember_token = Str::random(10);
        $user->save();
        $user->password_not_hashed = $password;

        $this->assertDatabaseHas('users', [
            'name' => 'Tom',
            'email' => 'tomleesm@gmail.com'
        ]);

        return $user;
    }

    /**
     * Markdown 語法測試
     */
    public function testMarkdown() {
        $this->browse(function (Browser $browser) {
            $user = $this->createOneUser();

            // 使用者 Tom 登入，編輯條目 home
            $browser->loginAs(1)
                    ->visit('/')
                    ->assertSee('Tom')
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    // 標題 h1 到 h6
                    ->type('article[content]', '# h1')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '## h2')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '### h3')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '#### h4')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '##### h5')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '###### h6')
                    ->assertSeeIn('.preview', 'h1')
                    ->assertSeeIn('.preview', 'h2')
                    ->assertSeeIn('.preview', 'h3')
                    ->assertSeeIn('.preview', 'h4')
                    ->assertSeeIn('.preview', 'h5')
                    ->assertSeeIn('.preview', 'h6')
                    ->assertSourceHas('<h1 id="h1">h1</h1>')
                    ->assertSourceHas('<h2 id="h2">h2</h2>')
                    ->assertSourceHas('<h3 id="h3">h3</h3>')
                    ->assertSourceHas('<h4 id="h4">h4</h4>')
                    ->assertSourceHas('<h5 id="h5">h5</h5>')
                    ->assertSourceHas('<h6 id="h6">h6</h6>')
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas('<h1 id="h1">h1</h1>')
                    ->assertSourceHas('<h2 id="h2">h2</h2>')
                    ->assertSourceHas('<h3 id="h3">h3</h3>')
                    ->assertSourceHas('<h4 id="h4">h4</h4>')
                    ->assertSourceHas('<h5 id="h5">h5</h5>')
                    ->assertSourceHas('<h6 id="h6">h6</h6>')
                    // 水平線
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '___')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '---')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '***')
                    ->assertSourceHas("<hr>\n<hr>\n<hr>")
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas("<hr>\n<hr>\n<hr>")
                    // Typographic replacements © © ® ® ™ ™ § § ±
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '(c) (C) (r) (R) (tm) (TM) (p) (P) +-')
                    ->assertSeeIn('.preview', '© © ® ® ™ ™ § § ±')
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSee('© © ® ® ™ ™ § § ±')
                    // 粗體、斜體和刪除線
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '**This is bold text 1**')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '__This is bold text 2__')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '*This is italic text 1*')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '_This is italic text 2_')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '~~Strikethrough~~')
                    ->assertSourceHas('<strong>This is bold text 1</strong>')
                    ->assertSourceHas('<strong>This is bold text 2</strong>')
                    ->assertSourceHas('<em>This is italic text 1</em>')
                    ->assertSourceHas('<em>This is italic text 2</em>')
                    ->assertSourceHas('<s>Strikethrough</s>')
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas('<strong>This is bold text 1</strong>')
                    ->assertSourceHas('<strong>This is bold text 2</strong>')
                    ->assertSourceHas('<em>This is italic text 1</em>')
                    ->assertSourceHas('<em>This is italic text 2</em>')
                    ->assertSourceHas('<s>Strikethrough</s>')
                    // 無序清單
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '+ Create a list by starting a line with `+`, `-`, or `*`')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '+ Sub-lists are made by indenting 2 spaces:')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '  - Marker character change forces new list start:')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '    * Ac tristique libero volutpat at')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '    + Facilisis in pretium nisl aliquet')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '    - Nulla volutpat aliquam velit')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '+ Very easy!')
                    ->assertSourceHas('<li>Create a list by starting a line with <code>+</code>, <code>-</code>, or <code>*</code></li>')
                    ->assertSourceHas("<li>Sub-lists are made by indenting 2 spaces:\n<ul>")
                    ->assertSourceHas("<li>Marker character change forces new list start:\n<ul>")
                    ->assertSourceHas("<li>Ac tristique libero volutpat at</li>\n</ul>")
                    ->assertSourceHas("<ul>\n<li>Facilisis in pretium nisl aliquet</li>\n</ul>")
                    ->assertSourceHas("<ul>\n<li>Nulla volutpat aliquam velit</li>\n</ul>\n</li>\n</ul>\n</li>")
                    ->assertSourceHas("<li>Very easy!</li>")
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas('<li>Create a list by starting a line with <code>+</code>, <code>-</code>, or <code>*</code></li>')
                    ->assertSourceHas("<li>Sub-lists are made by indenting 2 spaces:\n<ul>")
                    ->assertSourceHas("<li>Marker character change forces new list start:\n<ul>")
                    ->assertSourceHas("<li>Ac tristique libero volutpat at</li>\n</ul>")
                    ->assertSourceHas("<ul>\n<li>Facilisis in pretium nisl aliquet</li>\n</ul>")
                    ->assertSourceHas("<ul>\n<li>Nulla volutpat aliquam velit</li>\n</ul>\n</li>\n</ul>\n</li>")
                    ->assertSourceHas("<li>Very easy!</li>")
                    // 有序清單
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '1. You can use sequential numbers...')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '1. ...or keep all the numbers as `1.`')
                    ->assertSourceHas("<ol>\n<li>You can use sequential numbers…</li>\n<li>…or keep all the numbers as <code>1.</code></li>\n</ol>")
                    ->type('article[content]', '57. foo')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '1. bar')
                    ->assertSourceHas('<ol start="57">')
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas('<ol start="57">')
                    // 表格
                    ->clickLink('Edit')
                    ->assertPathIs('/edit/home')
                    ->type('article[content]', '| Option | Description |')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '| ------ | -----------: |')
                    ->keys('#editArticleContent', ['{return_key}'])
                    ->append('article[content]', '| A | B |')
                    ->assertSourceHas('<th style="text-align:right">Description</th>')
                    ->assertSourceHas('<td>A</td>')
                    ->assertSourceHas('<td style="text-align:right">B</td>')
                    ->click('@edit-save-button')
                    ->assertPathIs('/read/home')
                    ->assertSourceHas('<th style="text-align:right">Description</th>')
                    ->assertSourceHas('<td>A</td>')
                    ->assertSourceHas('<td style="text-align:right">B</td>');
        });
    }
}
