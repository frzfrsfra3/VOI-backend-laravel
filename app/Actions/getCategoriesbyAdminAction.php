<?php
namespace App\Actions;

use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class getCategoriesbyAdminAction 
{
    protected $CategoryService;

    public function __construct(CategoryRepository $CategoryService) 
    {
        $this->CategoryService = $CategoryService;
    }

    public function __invoke()
    {
        // Implement action functionality
          return $this->CategoryService->getcategoriesbyadmin();
    
    }
}