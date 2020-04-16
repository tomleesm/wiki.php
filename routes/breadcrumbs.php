<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', url('/'));
});

Breadcrumbs::for('article', function ($trail, $article) {
    if(isset($article->parent) && ! empty($article->parent)) {
        $trail->parent('article', $article->parent);
    } else {
        $trail->parent('home');
    }

    $trail->push($article->title, route('article.show', $article->title));
});
