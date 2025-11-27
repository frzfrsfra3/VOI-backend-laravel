<?php
namespace App\Actions;

use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class CreateCategoryAction 
{
    protected $CategoryService;

    public function __construct(CategoryRepository $CategoryService) 
    {
        $this->CategoryService = $CategoryService;
    }

    public function __invoke(Request $request)
    {
        // Implement action functionality
          return $this->CategoryService->createCategory($data);
    
    }
}