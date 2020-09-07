<?php

Breadcrumbs::for('article', function ($trail, $article) {
    if(isset($article->parent) && ! empty($article->parent)) {
        $trail->parent('article', $article->parent);
    }

    $trail->push($article->title, route('article.show', $article->title));
});
