<?php
namespace App\Actions\Admin;

use App\Repository\ArticleRepository;
use Illuminate\Http\Request;

class UpdateArticleActionByAdmin
{
    protected ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request, $id)
    {
        return $this->repository->updateArticleByAdmin($request, $id);
    }
}
