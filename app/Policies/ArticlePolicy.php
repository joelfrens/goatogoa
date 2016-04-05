<?php

namespace App\Policies;

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

    public function destroy(\App\User $user, \App\Article $article)
    {
        return $user->id === $article->user_id;
    }
}
