<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    
     public function view(User $user)
     {
         return $user->can('articles.view');
     }
     
     public function create(User $user)
     {
         return $user->can('articles.create');
     }
     
     public function update(User $user, Article $article)
     {
         return $user->can('articles.update');
     }
     
     public function delete(User $user, Article $article)
     {
         return $user->can('articles.delete');
     }
     

}
