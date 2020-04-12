<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 如果有記住之前的網址，就用它，否則使用首頁
        if($request->session()->has('backUrl'))
            return redirect(session('backUrl'));

        return redirect()->route('article.show', ['title' => 'home']);
    }
}
