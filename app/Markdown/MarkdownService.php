<?php
namespace App\Markdown;

use Mews\Purifier\Facades\Purifier;
use App\Markdown\TomParsedown;
use App\Markdown\TomParsedownToC;
use App\Article;

class MarkdownService
{
    private $markdown = '';

    public function toHTML($markdown) {
        $this->markdown = $markdown;

        // [[include:test]]
        $this->include();

        // 只轉內文，不產生目錄
        $this->markdownToHTMLNoTOC();
        $this->removeTagNotoc();

        // wiki link [[wiki]]
        $this->wikiLink();
        // 待辦事項
        $this->taskList();
        // emoji 表情文字
        $this->emoji();
        // XSS 過濾
        $this->xss_filter();

        return $this->markdown;
    }

    // [[include:test]]: 內嵌條目 test 的 markdown 內容
    private function include() {
        // 開頭是 include: 的 [[]]
        $this->markdown = preg_replace_callback('/\[\[include:([^\]]+)\]\]/', function($matches) {
            $title = 'include:' . $matches[1];

            $content = Article::where('title', $title)->value('content');

            // url = /edit/include:test
            $editURL = sprintf('/edit/%s', urlEncode($title));
            // 如果</div> 和 $content 沒有空一行，會無法轉換 $content 爲 html
            return <<<LINK
<div>
  <a href="{$editURL}">edit</a>
</div>

$content
LINK;
        }, $this->markdown);
    }

    private function markdownToHTMLNoTOC() {
        // 支援 table rowspan 和 colspan
        if($this->hasTagNotoc($this->markdown)) {
            $p = new TomParsedown();
            $p->setSafeMode(false);
            $this->markdown = $p->text($this->markdown);
        } else {
            $p = new TomParsedownToC();
            $p->setSafeMode(false);
            $this->markdown = $p->body($this->markdown);
        }
    }

    // 產生目錄
    public function toTOC($markdown) {
        if( $this->hasTagNotoc($markdown) ) return '';

        $p= new \ParsedownToC();
        $p->body($markdown);
        return $p->contentsList();
    }

    // 轉換 [[]] 爲 wiki link
    private function wikiLink() {
        $this->markdown = preg_replace_callback('/\[\[([^\]]+)\]\]/', function($matches) {
            // 抓取 [[test]] 之間的文字
            $linkText = $matches[1];
            // url = /read/test
            $URL = sprintf('/read/%s', urlEncode($linkText));
            // 回傳 <a href="/read/test">test</a>
            return sprintf('<a href="%s">%s</a>', $URL, $linkText);
        }, $this->markdown);
    }

    private function removeTagNotoc() {
        // 刪除 [notoc]
        $this->markdown = str_replace('<p>[notoc]</p>', '', $this->markdown);
    }

    private function hasTagNotoc($markdown) {
        return strpos($markdown, '[notoc]') !== false;
    }

    /**
     * XSS 過濾器
     *
     * 爲了讓內嵌 YouTube 的 iframe 標籤可以不用被 escape
     * 所以不用 Parsedown 的 setSafeMode(true)
     * 而是用 HTMLPurifier
     * mews/purifier 不知道爲什麼不能用
     **/
    private function xss_filter() {
        $config = \HTMLPurifier_Config::createDefault();
        // 啓用 html id
        $config->set('Attr.EnableID', true);
        // 啓用 html5 的 id 值規定：只要它不包含空格並且至少是一個字符即可
        // 這樣才能點選目錄跳到該標題
        $config->set('Attr.ID.HTML5', true);
        //allow YouTube and Vimeo
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
        // 有列在$allowTags 的標籤才能使用，否則標籤會直接拿掉
        $allowTags = explode(',', 'div,b,strong,i,em,u,a,ul,ol,li,p,hr,br,span,img,iframe,code,pre,h1,h2,h3,h4,h5,h6,del,table,tbody,th,tr,td,input');
        $config->set('HTML.AllowedElements', $allowTags);
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');

        // 需要定義 <input>，否則顯示 Element 'input' is not supported
        $def->addElement(
          'input',   // name
          'Inline',  // content set
          'Empty', // not allowed children
          'Common', // attribute collection
          array( // attributes
            'type' => 'Text',
            'name' => 'ID',
            'checked' => 'Bool',
            'disabled' => 'Bool',
          )
        );

        $purifier = new \HTMLPurifier($config);
        $this->markdown = $purifier->purify($this->markdown);
    }

    private function taskList() {
        if (strpos($this->markdown, '[x]') !== false || strpos($this->markdown, '[ ]') !== false) {
            $this->markdown = str_replace(['[x]', '[ ]'], [
                '<input type="checkbox" name="task[]" disabled="true" checked="true">',
                '<input type="checkbox" name="task[]" disabled="true">',
            ], $this->markdown);
        }
    }

    // emoji 表情文字
    private function emoji() {
        $this->markdown = \LitEmoji\LitEmoji::encodeUnicode($this->markdown);
    }
}
