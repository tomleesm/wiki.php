<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
        // 檢查是否有這個條目
    public static function exist($title) {
        return Article::where('title', $title)->count() > 0;
    }

    /**
     * 查詢關聯至條目的角色
     *
     * @return \App\Role
     */
    public function role() {
        return $this->belongsTo('App\Role');
    }
}
