<?php

namespace App\Actions;

use App\Repository\ArticleRepository;

class GetArticleDetailsAction
{
    public function __construct(
        protected ArticleRepository $articles
    ) {}

    public function __invoke($slug)
    {
        $article = $this->articles->findBySlug($slug);

        return [
            'article' => $article,
            'related' => $this->articles->related($article)
        ];
    }
}
