<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
        // 檢查是否有這個條目
    public static function exist($title) {
        return Article::where('title', $title)->count() > 0;
    }
}
