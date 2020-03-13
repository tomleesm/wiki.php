<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    /**
     * show an article page
     */
    public function show($title)
    {
        $article = Article::where('title', $title)->first();
        return view('article.show')->with('article', $article);
    }

    /**
     * edit an article
     */
    public function edit($title)
    {
        return view('article.edit')->with('title', $title);
    }

    /**
     * update an article
     */
    public function update($title, Request $request)
    {
        // 條目標題和內容
        $title = $request->input('article.title');
        $content = $request->input('article.content');

        // 用標題檢查條目是否存在，沒有的話先新增條目，有的話就更新內容
        $article = Article::where('title', $title)->get();

        if(empty($article->all())) {
            $newArticle = new Article();
            $newArticle->title = $title;
            $newArticle->save();
        }

        $article = Article::where('title', $title)->first();
        $article->content = $content;
        $article->save();

        return redirect()->route('article.show', ['title' => $title]);
    }
}
