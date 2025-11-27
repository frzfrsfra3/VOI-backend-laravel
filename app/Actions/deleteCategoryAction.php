<?php
namespace App\Actions;

use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class deleteCategoryAction 
{
    protected $CategoryService;

    public function __construct(CategoryRepository $CategoryService) 
    {
        $this->CategoryService = $CategoryService;
    }

    public function __invoke($id)
    {
        // Implement action functionality
          return $this->CategoryService->deletecategory($id);
    
    }
}