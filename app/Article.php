<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Article extends Model
{
    use SearchableTrait;
    use RevisionableTrait;

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
            'articles.content' => 1,
        ],
    ];

    // 列入歷史記錄的資料庫欄位
    protected $keepRevisionOf = ['content', 'parent'];
}
