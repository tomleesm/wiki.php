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
        // Parses the markdown text except the ToC tag. This also searches
        // the list of contents and available to get from "contentsList()"
        // method.
        $html = $this->body($text);

        $tag_origin  = $this->getTagToC();

        $toc_data = $this->contentsList();
        $toc_id   = $this->getIdAttributeToC();
        $needle  = '<p>' . $tag_origin . '</p>';
        $replace = "<div id=\"${toc_id}\">${toc_data}</div>";

        return $replace . $html;
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

    private function removeToCTag($html) {
        // 刪除 [toc]
        return str_replace('<p>[toc]</p>', '', $html);
    }
}
