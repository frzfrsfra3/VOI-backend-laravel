<?php
namespace App\Actions\Admin;

use App\Repository\ArticleRepository;
use Illuminate\Http\Request;
class GetArticlesByAdmin
{
    protected ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke()
    {
        return $this->repository->getArticlesbyadmin();
    }
}
