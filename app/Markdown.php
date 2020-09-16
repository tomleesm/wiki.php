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

    /**
     * 覆寫 text()，不需要 [toc]，預設顯示目錄
     *
     * @param string $text
     *
     * @return string
     */

    public function text($text) {
        $html = $this->body($text);

        if ($this->hasTagNotoc($text)) {
            return $this->removeTagNotoc($html);
        }

        $toc = sprintf("<div id=\"%s\">%s</div>",
                        $this->getIdAttributeToC(),
                        $this->contentsList());

        return $toc . $html;
    }

    public function toHTML() {
        // 把 markdown 轉換成 html
        $html = $this->text($this->markdown);
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

    private function hasTagNotoc($text) {
        return strpos($text, '[notoc]') !== false;
    }
}
