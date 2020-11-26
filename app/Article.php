<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use \Nicolaslopezj\Searchable\SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'articles.title' => 1,
        ]
    ];

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
