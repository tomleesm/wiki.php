<?php
namespace App;

class Markdown extends \ParsedownToC
{
    private $markdown = '';

    public function __construct($markdown) {
        // 防止 XSS
        $this->setSafeMode(true);

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
        return $this->convertWikiLinks($html);
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
}
