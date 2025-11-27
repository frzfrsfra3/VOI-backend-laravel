<?php
namespace App\Actions\Article;

use App\Repository\ArticleRepository;
use Illuminate\Http\Request;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
class ShowRelatedArticlesAction
{
    protected ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {

        $this->repository = $repository;
    }

    public function __invoke($id)
    {
        try {
            // $filters = [
            //     'search' => $request->get('search'),
            //     'category' => $request->get('category'),
            //     'sort' => $request->get('sort', 'latest'),
            //     'per_page' => $request->get('per_page', 10)
            // ];
            // $article = Article::with('categories')->findOrFail($id);
            // $article = Article::findOrFail($id);
    // dd($filters);
           return $this->repository->related($id);
        // return $this->repository->getHomepageArticles($request);
    }
    catch (\Exception $e) {
        return ApiResponseHelper::error(
            'Failed to fetch articles',
            ApiResponseCodes::SERVER_ERROR
        );
    }
    }
}
