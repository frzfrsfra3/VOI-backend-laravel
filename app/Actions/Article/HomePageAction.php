<?php
namespace App\Actions\Article;

use App\Repository\ArticleRepository;
use Illuminate\Http\Request;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
class HomePageAction
{
    protected ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {

        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'category' => $request->get('category'),
                'sort' => $request->get('sort', 'latest'),
                'per_page' => $request->get('per_page', 10)
            ];
    // dd($filters);
           return $this->repository->getHomepageArticles($filters);
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
