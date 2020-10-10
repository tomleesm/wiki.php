<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Markdown\Markdown;

class MarkdownTest extends TestCase
{
    /**
     * h1 to h6
     *
     * @dataProvider headingsProvider
     * @return void
     */
    public function testHeadings($html, $markdown) {
        $this->assertEquals($html, Markdown::toHTML($markdown));
        $this->assertEmpty(Markdown::toTOC($markdown));
    }

    public function headingsProvider() {
        return [
            ["\n<h1>h1 標題</h1>", "[notoc]\n# h1 標題"],
            ["\n<h2>h2 標題</h2>", "[notoc]\n## h2 標題"],
            ["\n<h3>h3 標題</h3>", "[notoc]\n### h3 標題"],
            ["\n<h4>h4 標題</h4>", "[notoc]\n#### h4 標題"],
            ["\n<h5>h5 標題</h5>", "[notoc]\n##### h5 標題"],
            ["\n<h6>h6 標題</h6>", "[notoc]\n###### h6 標題"],
        ];
    }

    /**
     * 目錄
     *
     * @dataProvider TOCProvider
     * @return void
     */
    public function testTOC($markdown, $bodyHTML, $tocHTML) {
        $this->assertEquals($bodyHTML, Markdown::toHTML($markdown));
        $this->assertEquals($tocHTML, Markdown::toTOC($markdown));
    }

    public function TOCProvider() {
        return [
            [ "# h1 標題",
              '<h1 id="' . urlencode('h1 標題') . '">h1 標題</h1>',
              "<ul>\n" . '<li><a href="#' . urlencode('h1 標題') . '">h1 標題</a></li>' . "\n</ul>"],
            [ "## h2 標題",
              '<h2 id="' . urlencode('h2 標題') . '">h2 標題</h2>',
              "<ul>\n" . '<li><a href="#' . urlencode('h2 標題') . '">h2 標題</a></li>' . "\n</ul>"],
            [ "### h3 標題",
              '<h3 id="' . urlencode('h3 標題') . '">h3 標題</h3>',
              "<ul>\n" . '<li><a href="#' . urlencode('h3 標題') . '">h3 標題</a></li>' . "\n</ul>"],
            [ "#### h4 標題",
              '<h4 id="' . urlencode('h4 標題') . '">h4 標題</h4>',
              "<ul>\n" . '<li><a href="#' . urlencode('h4 標題') . '">h4 標題</a></li>' . "\n</ul>"],
            [ "##### h5 標題",
              '<h5 id="' . urlencode('h5 標題') . '">h5 標題</h5>',
              "<ul>\n" . '<li><a href="#' . urlencode('h5 標題') . '">h5 標題</a></li>' . "\n</ul>"],
            [ "###### h6 標題",
              '<h6 id="' . urlencode('h6 標題') . '">h6 標題</h6>',
              "<ul>\n" . '<li><a href="#' . urlencode('h6 標題') . '">h6 標題</a></li>' . "\n</ul>"],
        ];
    }

    /**
     *
     * @dataProvider markdownProvider
     * @return void
     */
    public function testMarkdown($markdown, $html) {
        $this->assertEquals($html, Markdown::toHTML($markdown));
    }

    public function markdownProvider() {
        return [
            // 水平分隔線
            ['___', '<hr />'],
            ['---', '<hr />'],
            ['***', '<hr />'],
            // 強調
            ['**粗體文字**', '<p><strong>粗體文字</strong></p>'],
            ['__粗體文字__', '<p><strong>粗體文字</strong></p>'],
            ['*斜體文字*', '<p><em>斜體文字</em></p>'],
            ['_斜體文字_', '<p><em>斜體文字</em></p>'],
            ['~~刪除文字~~', '<p><del>刪除文字</del></p>'],
            // 引用區塊
            ["> 引用區塊也可以是巢狀的喔\n>> 可以多層次的使用\n> > > 或是用空白隔開", "<blockquote>\n<p>引用區塊也可以是巢狀的喔</p>\n<blockquote>\n<p>可以多層次的使用</p>\n<blockquote>\n<p>或是用空白隔開</p>\n</blockquote>\n</blockquote>\n</blockquote>"],
            // 無序清單
            ["+ 在行開頭使用 `+` `-` 或是 `*` 來建立清單\n  + 空兩個空白就可以產生子清單",
             "<ul>\n<li>在行開頭使用 <code>+</code> <code>-</code> 或是 <code>*</code> 來建立清單\n<ul>\n<li>空兩個空白就可以產生子清單</li>\n</ul></li>\n</ul>"],
        ];
    }
}
