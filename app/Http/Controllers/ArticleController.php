<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * show an article page
     * return Illuminate\Http\Response
     */
    public function read()
    {
        return view('article.read');
    }
}
