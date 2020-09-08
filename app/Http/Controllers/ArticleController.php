<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Markdown;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * show an article page
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
        } else if ($count === 0 && $title != 'home') {
            return redirect()->route('article.edit', ['title' => $title, 'parent' => $breadcrumbParent]);
        } else if ($count !== 0) {
            // routes/breadcrumbs.php 的第二個設定是遞迴呼叫，
            // 所以 $article->parent 要指向 Article 物件，不能只是字串
            // 一樣要遞迴產生 Article 物件
            $article = $this->createArticleWithParent($title);
        }

        $markdown = new Markdown($article->content, $title);
        $article->content = $markdown->toHTML();

        return view('article.show')->with('article', $article);
    }

    /**
     * edit an article
     */
    public function edit($title, Request $request)
    {
        // 檢查是否有這個條目
        $count = Article::where('title', $title)->count();
        // 設定麵包屑的巢狀結構
        $breadcrumbParent = urlDecode($request->query('parent'));

        // 如果沒有這個條目
        $article = null;
        if($count === 0) {
            $article = new \stdClass();
            $article->title = $title;
            $article->content = '';
            $article->parent = $breadcrumbParent;
        } else {
            $article = Article::where('title', $title)->first();
        }

        return view('article.edit')->with('article', $article);
    }

    /**
     * update an article
     */
    public function update($title, Request $request)
    {
        // 條目標題和內容
        $id = Auth::id();
        $title = $request->input('article.title');
        $content = $request->input('article.content');
        // 麵包屑的上一層
        $parent = $request->input('article.parent');

        // 用標題檢查條目是否存在，沒有的話先新增條目，有的話就更新內容
        $article = Article::where('title', $title)->get();

        if(empty($article->all())) {
            $newArticle = new Article();
            $newArticle->user_id = $id;
            $newArticle->title = $title;
            $newArticle->save();
        }

        $article = Article::where('title', $title)->first();
        $article->user_id = $id;
        $article->content = $content;
        $article->parent = $parent;
        $article->save();

        return redirect()->route('article.show', ['title' => $title]);
    }

    private function createArticleWithParent($title) {
        $article = Article::where('title', $title)->first();

        if( ! empty($article->parent) )
            $article->parent = $this->createArticleWithParent($article->parent);

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
        // 抓取條目
        $article = Article::where('title', $title)->first();
        $markdown = new Markdown($article->content);
        $markdown->toPDF();
    }

    /**
     * 顯示歷史記錄
     */
    public function getHistories($title, Request $request) {
        $article = Article::where('title', $title)->first();

        $page = $this->getCurrentPage($request);
        $perPage = 1;
        $startIndex = $perPage * ( $page - 1);
        $histories = $article->revisionHistory->slice($startIndex, $perPage);

        return view('article.history')->with(['article' => $article, 'histories' => $histories]);
    }

    private function getCurrentPage($request) {
        $page = (int) $request->query('page');
        if(empty($page) || ! is_int($page)) return 1;
        else return $page;
    }

    public function renderMarkdown(Request $request) {
        $markdown = new Markdown($request->post('markdown'), $request->post('parent'));
        return $markdown->toHTML();
    }
}
