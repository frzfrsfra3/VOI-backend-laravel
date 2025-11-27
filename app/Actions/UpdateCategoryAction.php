<?php
namespace App\Actions;

use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class UpdateCategoryAction 
{
    protected $CategoryService;

    public function __construct(CategoryRepository $CategoryService) 
    {
        $this->CategoryService = $CategoryService;
    }

    public function __invoke(Request $request ,$id)
    {
        // Implement action functionality
          return $this->CategoryService->updatecategoryByAdmin($request, $id);
    
    }
}