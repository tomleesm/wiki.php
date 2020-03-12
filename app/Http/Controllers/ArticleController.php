<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * show an article page
     */
    public function show()
    {
        return view('article.show');
    }

    /**
     * edit an article
     */
    public function edit()
    {
        return view('article.edit');
    }

    /**
     * update and article
     */
    public function update(Request $request)
    {
        return $request->articleContent;
    }
}
