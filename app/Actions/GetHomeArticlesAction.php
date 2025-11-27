<?php

namespace App\Actions;

use App\Repository\ArticleRepository;
use App\Models\Category;
use Illuminate\Http\Request;

class GetHomeArticlesAction
{
    public function __construct(
        protected ArticleRepository $articles
    ) {}

    public function __invoke(Request $request)
    {
        return [
            'articles'   => $this->articles->search($request->all()),
            'categories' => Category::all()
        ];
    }
}
