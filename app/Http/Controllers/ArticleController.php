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
            return redirect()->route('article.edit', ['title' => $title]);
        // 有這個條目
        } else if ($count !== 0) {
            // 產生條目
            $article = Article::where('title', $title)->first();
        }

        // 把 markdown 語法轉成 HTML
        $markdown = new Markdown($article->content);
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

        $article = null;
        // 如果沒有這個條目
        if($count === 0) {
            // 新增一個空的條目，並設定標題和上層麵包屑
            $article = new \stdClass();
            $article->title = $title;
            $article->content = '';
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
        // 條目標題和內容
        $title = $request->input('article.title');
        $content = $request->input('article.content');

        // 用標題檢查條目是否存在，沒有的話先新增條目，有的話就更新內容
        $article = Article::where('title', $title)->get();

        // 沒有這個條目
        if(empty($article->all())) {
            // 新增條目
            $newArticle = new Article();
            $newArticle->title = $title;
            $newArticle->save();
        }

        // 更新條目內容、作者id、麵包屑上一層
        $article = Article::where('title', $title)->first();
        $article->content = $content;
        $article->save();

        // 跳轉到顯示條目
        return redirect()->route('article.show', ['title' => $title]);
    }

    /**
     * 產生編輯條目的預覽結果
     */
    public function renderMarkdown(Request $request) {
        $markdown = new Markdown($request->post('markdown'));
        return $markdown->toHTML();
    }
}
