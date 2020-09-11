<?php
namespace App;

use Dompdf\Dompdf;

class Markdown extends \ParsedownToC
{
    private $markdown = '';

    public function __construct($markdown) {
        // 防止 XSS
        $this->setSafeMode(true);

        $this->markdown = $markdown;
    }

    public function toHTML() {
        // 把 markdown 轉換成 html
        $html = $this->text($this->markdown);
        // 把 [[test]] 轉成連結 /read/test
        return $this->convertWikiLinks($html);
    }

    public function toPDF() {
        // 設定使用中文字型
        // 中文字型屬於自訂字型，不能用 $dompdf->set_option('defaultFont', 'wt011');
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>@font-face { font-family: "wt011"; } * { font-family: "wt011" }</style>';
        // markdown 轉換成 HTML。PDF 不產生目錄，所以是用 ParsedownToC->body()
        $html .= $this->body($this->markdown);

        // 刪除 [toc]
        $html = $this->removeToCTag($html);

        // HTML 轉換成 PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // 設定紙張大小和直橫式
        $dompdf->setPaper('A4', 'landscape');
        // 轉換 HTML 爲 PDF
        $dompdf->render();
        // 觸發下載 PDF 檔的 HTTP 請求
        $dompdf->stream();
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
