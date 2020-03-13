<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * show an article page
     */
    public function show($title)
    {
        return view('article.show')->with('title', $title);
    }

    /**
     * edit an article
     */
    public function edit()
    {
        return view('article.edit');
    }
}
