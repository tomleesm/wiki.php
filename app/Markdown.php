<?php
namespace App;

use Mews\Purifier\Facades\Purifier;

class Markdown extends \ParsedownToC
{
    private $markdown = '';

    public function __construct($markdown) {
        // 防止 XSS
        $this->setSafeMode(false);

        $this->markdown = $markdown;
    }

    public function toHTML() {
        // 把網址轉成內嵌 HTML
        // 只處理 markdown 只貼網址的部分
        // 不處理 [text](網址) 這種格式，所以先轉換 markdown
        // 而不是轉換後的 <p><a href="網址">網址</a></p>
        $this->urlToEmbedHTML();
        // 把 markdown 轉換成 html，但不處理 [toc]
        $html = $this->body($this->markdown);
        $html = '<div id="body">' . $html . '</div>';

        // 如果有 [notoc]
        if ($this->hasTagNotoc()) {
            return $this->removeTagNotoc($html);
        }

        // 產生目錄
        $toc = sprintf("<div id=\"%s\">%s</div>",
                        $this->getIdAttributeToC(),
                        $this->contentsList());

        $html .= $toc;
        // 把 [[test]] 轉成連結 /read/test
        $html = $this->convertWikiLinks($html);
        /* return $html; */
        return $this->xss_filter($html);
    }

    // 轉換 [[]] 爲 wiki link
    private function convertWikiLinks($html) {
        return preg_replace_callback('/\[\[([^\]]+)\]\]/', function($matches) {
            // 抓取 [[test]] 之間的文字
            $linkText = $matches[1];
            // url = /read/test
            $URL = sprintf('/read/%s', urlEncode($linkText));
            // 回傳 <a href="/read/test">test</a>
            return sprintf('<a href="%s">%s</a>', $URL, $linkText);
        }, $html);
    }

    private function removeTagNotoc($html) {
        // 刪除 [notoc]
        return str_replace('<p>[notoc]</p>', '', $html);
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
    private function xss_filter($html) {
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
        $allowTags = explode(',', 'div,b,strong,i,em,u,a,ul,ol,li,p,hr,br,span,img,iframe,code,pre,h1,h2,h3,h4,h5,h6,del,table,tbody,th,tr,td');
        $config->set('HTML.AllowedElements', $allowTags);
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('iframe', 'allowfullscreen', 'Bool');

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
