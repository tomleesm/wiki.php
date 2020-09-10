<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Markdown;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * 顯示條目
     */
    public function show($title, Request $request)
    {
        // 檢查是否有這個條目
        $count = Article::where('title', $title)->count();
        // 設定麵包屑的巢狀結構
        $breadcrumbParent = urlDecode($request->query('parent'));

        $article = null;
        // 沒有這個條目，而且條目爲 home
        if ($count === 0 && $title == 'home') {
            // 顯示預設歡迎訊息
            $article = new \stdClass();
            $article->title = 'home';
            $article->content = '';
        // 沒有這個條目，而且條目不是 home
        } else if ($count === 0 && $title != 'home') {
            // 跳轉到編輯頁面
            return redirect()->route('article.edit', ['title' => $title, 'parent' => $breadcrumbParent]);
        // 有這個條目
        } else if ($count !== 0) {
            // 產生條目和麵包屑
            $article = $this->createArticleWithParent($title);
        }

        // 把 markdown 語法轉成 HTML
        $markdown = new Markdown($article->content, $title);
        $article->content = $markdown->toHTML();

        return view('article.show')->with('article', $article);
    }

    /**
     * 顯示條目編輯頁面
     */
    public function edit($title, Request $request)
    {
        // 檢查是否有這個條目
        $count = Article::where('title', $title)->count();
        // 設定麵包屑的巢狀結構
        $breadcrumbParent = urlDecode($request->query('parent'));

        $article = null;
        // 如果沒有這個條目
        if($count === 0) {
            // 新增一個空的條目，並設定標題和上層麵包屑
            $article = new \stdClass();
            $article->title = $title;
            $article->content = '';
            $article->parent = $breadcrumbParent;
        } else {
            // 抓取條目
            $article = Article::where('title', $title)->first();
        }

        // 顯示條目編輯頁面
        return view('article.edit')->with('article', $article);
    }

    /**
     * 儲存條目
     */
    public function update($title, Request $request)
    {
        // 目前登入的使用者 id
        $id = Auth::id();
        // 條目標題和內容
        $title = $request->input('article.title');
        $content = $request->input('article.content');
        // 麵包屑的上一層
        $parent = $request->input('article.parent');

        // 用標題檢查條目是否存在，沒有的話先新增條目，有的話就更新內容
        $article = Article::where('title', $title)->get();

        // 沒有這個條目
        if(empty($article->all())) {
            // 新增條目
            $newArticle = new Article();
            $newArticle->user_id = $id;
            $newArticle->title = $title;
            $newArticle->save();
        }

        // 更新條目內容、作者id、麵包屑上一層
        $article = Article::where('title', $title)->first();
        $article->user_id = $id;
        $article->content = $content;
        $article->parent = $parent;
        $article->save();

        // 跳轉到顯示條目
        return redirect()->route('article.show', ['title' => $title]);
    }

    // routes/breadcrumbs.php 設定是遞迴呼叫
    // 所以 $article->parent 要指向 Article 物件，不能只是字串
    // 一樣要遞迴產生 Article 物件
    private function createArticleWithParent($title) {
        // 用標題抓取條目
        $article = Article::where('title', $title)->first();

        // 如果 parent 不是空的，表示它不是最上層條目
        if( ! empty($article->parent) ) {
            // 抓取更上層的條目
            $article->parent = $this->createArticleWithParent($article->parent);
        }

        return $article;
    }

    /**
     * 搜尋條目
     */
    public function search(Request $request) {
        $query = new \stdClass();
        $query->keyword = $request->input('keyword');
        $query->result = Article::search($query->keyword)->get();

        return view('article.search')
               ->with('query', $query);
    }

    /**
     * 匯出條目爲 PDF
     */
    public function exportToPDF($title) {
        // 用標題抓取條目
        $article = Article::where('title', $title)->first();
        $markdown = new Markdown($article->content);
        $markdown->toPDF();
    }

    /**
     * 顯示歷史記錄
     */
    public function getHistories($title, Request $request) {
        // 用標題抓取條目
        $article = Article::where('title', $title)->first();

        // 目前頁碼
        $page = $this->getCurrentPage($request);
        // 每頁只有一筆資料？
        $perPage = 1;
        // 這一頁從第幾筆開始
        $startIndex = $perPage * ( $page - 1);
        // 這一頁的歷史記錄顯示範圍
        $histories = $article->revisionHistory->slice($startIndex, $perPage);

        return view('article.history')->with(['article' => $article, 'histories' => $histories]);
    }

    // 目前頁碼
    private function getCurrentPage($request) {
        $page = (int) $request->query('page');
        if(empty($page) || ! is_int($page)) return 1;
        else return $page;
    }

    /**
     * 產生編輯條目的預覽結果
     */
    public function renderMarkdown(Request $request) {
        $markdown = new Markdown($request->post('markdown'),
                                 $request->post('parent'));
        return $markdown->toHTML();
    }
}
