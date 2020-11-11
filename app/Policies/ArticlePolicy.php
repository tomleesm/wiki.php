<?php

namespace App\Policies;

use App\User;
use App\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user = null, Article $article) {
        // 沒有限制
        if($article->is_restricted === false) return true;
        // 沒有登入
        if(is_null($user)) return false;
        // 使用者是 Admin
        if($user->role->name == 'Administrator') return true;
        // 限制是 Admin
        if($article->role->name == 'Administrator') return false;
        // 使用者是 Editor
        if($user->role->name == 'Editor') return true;
        // 限制是 Editor
        if($article->role->name == 'Editor') return false;
        // 限制是 Login user
        return true;
    }
}
