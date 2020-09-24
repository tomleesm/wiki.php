<?php
namespace App;

use Mews\Purifier\Facades\Purifier;

class Markdown
{
    private $markdown = '';

    public function __construct($markdown) {
        $this->markdown = $markdown;
    }

    public function toHTML() {
        // [[include:test]]
        $this->include();
        // 把網址轉成內嵌 HTML
        // 只處理 markdown 只貼網址的部分
        // 不處理 [text](網址) 這種格式，所以先轉換 markdown
        // 而不是轉換後的 <p><a href="網址">網址</a></p>
        $this->urlToEmbedHTML();

        // 如果有 [notoc]
        if ($this->hasTagNotoc()) {
            // 只轉內文，不產生目錄
            $this->markdownToHTMLNoTOC();
            $this->removeTagNotoc();
        } else {
            // 產生內文並加上目錄
            $this->createBodyAndTOC();
        }

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
        $p = new \ParsedownTablespan();
        $p->setSafeMode(false);
        $this->markdown = '<div id="body">' . $p->text($this->markdown) . '</div>';
    }

    // 產生內文並加上目錄
    private function createBodyAndTOC() {
        // 支援 table rowspan 和 colspan
        $p = new \ParsedownTablespan();
        $p->setSafeMode(false);
        $body = '<div id="body">' . $p->text($this->markdown) . '</div>';

        $pForTOC = new \ParsedownToC();
        $pForTOC->body($this->markdown);
        $toc  = '<div id="toc">' . $pForTOC->contentsList() . '</div>';

        $this->markdown = $body . $toc;
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

    private function hasTagNotoc() {
        return strpos($this->markdown, '[notoc]') !== false;
    }

    private function urlToEmbedHTML() {
        $markdown = $this->markdown;

        $links = [];
        // 只抓開頭和結尾是換行符號的網址
        // 只用 \n 或 \r 代表換行，Read 和 Edit 預覽會不一致，用 \R 就可以了
        $regex = '~\R\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/|_))\R~i';
        if( preg_match_all($regex, $markdown, $matches) ) {
            $links = array_filter($matches['0']);
        }

        $embera = new \Embera\Embera();
        foreach($links as $link) {
            // 轉換網址爲內嵌 HTML
            $embedHTML = '<p>' . $embera->autoEmbed($link) . '</p>';
            // 把網址取代爲內嵌 HTML
            $markdown = str_replace($link, $embedHTML, $markdown);
        }

        $this->markdown = $markdown;
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
