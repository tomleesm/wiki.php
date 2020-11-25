<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Role;
use App\Markdown\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->only(['auth']);
    }

    /**
     * 檢視條目
     */
    public function show($titleEncoded)
    {
        $title = rawurldecode($titleEncoded);
        $article = null;
        // 有這個條目
        if (Article::exist($title)) {
            $article        = Article::where('title', $title)->first();
            $article->exist = true;
        // 沒有這個條目
        } else {
            $article          = new \stdClass();
            $article->title   = $title;
            $article->content = '';
            $article->exist   = false;

            // 在 新增條目頁面 讀取 $title
            // 因爲路由是 GET /articles/create
            session()->put('articleTitle', $title);
        }

        // 把條目存到 session，用來顯示在搜尋框
        // 抓取之前存的條目
        $articlesVisited = session()->get('articles.visited', []);
        if(count($articlesVisited) == 10) {
            // 把陣列第一筆刪掉
            array_shift($articlesVisited);
        }
        // 再存入新的
        array_push($articlesVisited, $title);

        // 去除重複的條目
        $articlesVisited = array_unique($articlesVisited);

        session()->put('articles.visited', $articlesVisited);

        // 把 markdown 語法轉成 HTML
        $article->body = Markdown::toHTML($article->content);
        $article->toc  = Markdown::toTOC($article->content);

        return view('articles.show')->with('article', $article);
    }

    /**
     * 編輯條目頁面
     */
    public function edit($titleEncoded)
    {
        $title = rawurldecode($titleEncoded);
        $article        = Article::where('title', $title)->first();
        $article->exist = true;

        // 合併 collection 和 eloquent collection 不容易
        // 直接用陣列比較簡單
        // 選項 anyone
        $option = [[
          'id' => 'anyone',
          'name' => 'Anyone',
        ]];
        // 再把表格 roles 的選項加進來
        $options = array_merge($option, Role::get()->toArray());

        // 決定 option selected
        // SQLite 使用整數 1 和 0 表示 true/false
        // 所以不用 === false，而是 == false
        if($article->is_restricted == false) {
            $options[0]['selected'] = 'selected';
        } else {
            $options[$article->role_id]['selected'] = 'selected';
        }
        // 顯示條目編輯頁面
        return view('articles.edit', [
            'article' => $article,
            'options' => $options,
        ]);
    }

    /**
     * 新增條目頁面
     */
    public function create() {
        $article        = new \stdClass();
        $article->title = session('articleTitle');
        $article->exist = false;

        return view('articles.create')->with('article', $article);
    }

    /**
     * 新增條目
     */
    public function store(Request $request) {
        $article          = new Article();
        $article->title   = $request->input('article.title');
        $article->content = $request->input('article.content');
        $article->save();

        // 刪除 新增條目頁面 的 session articleTitle
        session()->forget('articleTitle');

        return redirect()->route('articles.show',
                                 ['title' => $request->input('article.title')]);
    }
    /**
     * 更新條目
     */
    public function update($title, Request $request)
    {
        // 條目標題和內容
        $title = $request->input('article.title');

        $article          = Article::where('title', $title)->first();

        $this->authorize('update', $article);

        $article->content = $request->input('article.content');
        $article->save();

        // 跳轉到 檢示條目
        return redirect()->route('articles.show', ['title' => $title]);
    }

    /**
     * 預覽條目
     */
    public function preview(Request $request) {
        return Markdown::toHTML($request->post('markdown'));
    }

    /**
     * 條目權限
     */
    public function auth($articleId, Request $request) {
        $this->authorize('auth', Auth::user());

        DB::transaction(function () use ($articleId, $request) {
            $roleId = $request->input('roleId');
            $article = Article::findOrFail($articleId);

            if($roleId == 'anyone') {
                $article->is_restricted = false;
            } else {
                $article->is_restricted = true;
                $article->role_id = $roleId;
            }

            $article->save();
        });

        // 成功修改，回傳成功訊息
        return response()->json([
            'status' => 'success',
            'message' => 'Change role successfully',
        ]);
    }
}
