<?php
namespace App;

use Dompdf\Dompdf;

class Markdown extends \ParsedownToC
{
    private $markdown = '';
    private $breadcrumbParent = '';

    public function __construct($markdown, $breadcrumbParent = '') {
        parent::__construct();
        // 防止 XSS
        $this->setSafeMode(true);

        $this->markdown = $markdown;
        $this->breadcrumbParent = $breadcrumbParent;
    }

    public function toHTML() {
        $html = $this->text($this->markdown);
        return $this->convertWikiLinks($html);
    }

    public function toPDF() {
        // markdown 轉換成 HTML
        // PDF 不產生目錄，所以是用 ParsedownToC->body()
        // 設定使用中文字型
        // 中文字型屬於自訂字型，不能用 $dompdf->set_option('defaultFont', 'wt011');
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>@font-face { font-family: "wt011"; } * { font-family: "wt011" }</style>';
        $html .= $this->body($this->markdown);

        $html = $this->removeToCTag($html);

        // HTML 轉換成 PDF
        $dompdf = new Dompdf();
        //$dompdf->loadHtml($html);
        $dompdf->loadHtml($html);
        // 設定紙張大小和直橫式
        $dompdf->setPaper('A4', 'landscape');
        // 轉換 HTML 爲 PDF
        $dompdf->render();
        // 下載到 client
        $dompdf->stream();
    }

    private function convertWikiLinks($html) {
        // 轉換 [[]] 爲 wiki link
        return preg_replace_callback('/\[\[([^\]]+)\]\]/', function($matches) {
            $linkText = $matches[1];
            $URL = sprintf('/read/%s?parent=%s', urlEncode($linkText), urlEncode($this->breadcrumbParent));
            return sprintf('<a href="%s">%s</a>', $URL, $linkText);
        }, $html);
    }

    private function removeToCTag($html) {
        // 刪除 [toc]
        return str_replace('<p>[toc]</p>', '', $html);
    }
}
