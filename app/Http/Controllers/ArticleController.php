<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Markdown\Markdown;
use App\Image;;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * 檢視條目
     */
    public function show($title)
    {
        $article = null;
        // 有這個條目
        if (Article::exist($title)) {
            $article = Article::where('title', $title)->first();
            $article->exist = true;
        // 沒有這個條目
        } else {
            $article = new \stdClass();
            $article->title = $title;
            $article->content = '';
            $article->exist = false;
            // 在 新增條目頁面 讀取 $title
            // 因爲路由是 GET /articles/create
            session()->put('articleTitle', $title);
        }

        // 把 markdown 語法轉成 HTML
        $article->body = Markdown::toHTML($article->content);
        $article->toc = Markdown::toTOC($article->content);

        return view('articles.show')->with('article', $article);
    }

    /**
     * 顯示條目編輯頁面
     */
    public function edit($title)
    {
        $article = null;
        // 有這個條目
        if(Article::exist($title)) {
            $article = Article::where('title', $title)->first();
            $article->exist = true;
        // 如果沒有這個條目
        } else {
            // 新增一個空的條目，並設定標題
            $article = new \stdClass();
            $article->title = $title;
            $article->content = '';
            $article->exist = false;
        }

        // 顯示條目編輯頁面
        return view('articles.edit')->with('article', $article);
    }

    /**
     * 新增條目頁面
     */
    public function create() {
        // 新增一個空的條目，並設定標題
        $article = new \stdClass();
        $article->title = session('articleTitle');
        $article->exist = false;

        return view('articles.create')->with('article', $article);
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

        // 刪除 新增條目頁面 的 session articleTitle
        session()->forget('articleTitle');

        // 跳轉到顯示條目
        return redirect()->route('articles.show', ['title' => $title]);
    }

    /**
     * 產生編輯條目的預覽結果
     */
    public function renderMarkdown(Request $request) {
        return Markdown::toHTML($request->post('markdown'));
    }

    /**
     * 上傳圖片
     *
     * @param Request $request
     *
     * @return json
     */

    public function uploadImage(Request $request) {
        $requestImage = $request->file('image');

        // 如果上傳失敗，回傳錯誤訊息
        if( ! $requestImage->isValid()) {
            return  response()->json([
                'status' => 'upload file fails',
                'error_code' => $requestImage->getError(),
                'error_message' => $requestImage->getErrorMessage(),
            ]);
        }

        // 儲存檔案到資料庫，回傳 id
        $id = Image::store($requestImage);

        return response()->json([
            'status' => 'upload file successfully',
            'originalName' => $requestImage->getClientOriginalName(),
            'id' => $id, // UUID
        ]);
    }

    /**
     * 顯示圖片
     */
    public function showImage($id) {
        if( ! Str::isUuid($id) ) {
            return abort(404);
        }

        $image = Image::findOrFail($id);

        // 在瀏覽器中直接顯示圖片
        return response()->stream(function() use ($image) {
                   fpassthru($image->content);
               }, 200,
                   // content-type 設定成 image/* ，會跳出檔案儲存對話恇
                   ['Content-Type' => 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/tiff,image/webp']
               );
    }
}
