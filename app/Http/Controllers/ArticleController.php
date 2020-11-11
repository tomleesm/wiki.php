<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
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

        // 顯示條目編輯頁面
        return view('articles.edit')->with('article', $article);
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
