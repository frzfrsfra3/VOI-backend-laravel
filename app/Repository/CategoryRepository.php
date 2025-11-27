<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\Models\Category;
use App\Interfaces\CategoryInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
class CategoryRepository extends BaseRepositoryImplementation implements CategoryInterface
{
    public function model()
    {
        return Category::class;
    }

    // Implement any additional methods here
  public function createCategory(Request $request)
{
    $startTime = microtime(true);
    $rules = [
  
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        // Extract the first validation message
        $firstErrorMessage = $validator->errors()->first();

        return ApiResponseHelper::sendErrorResponse(
            new ErrorResult(
                $validator->errors(),
                $firstErrorMessage, // Use the first validation message here
                null,
                false,
                400
            ),
            400
        );
    }

    $r = $this->create($validator->validated());

    return ApiResponseHelper::sendResponse(new Result($r, 'Category created successfully'));
}

public function createcategorybyAdmin(Request $request)
{
    // 1) التحقق من المدخلات
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description'=>'string'
    ]);

    if ($validator->fails()) {
        return ApiResponseHelper::validationError(
            $validator->errors(),
            $validator->errors()->first()
        );
    }

    $data = $validator->validated();
   
    // 2) توليد slug فريد
    $baseSlug = \Str::slug($data['name']);
    $slug = $baseSlug;
    $counter = 1;

    while ($this->model->where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter++;
    }

    $data['slug'] = $slug;

   
    // 4) إنشاء المقال
    $category = $this->model->create($data);

  

    
    return ApiResponseHelper::sendResponse(
        new Result($category, 'category created successfully')
    );
}


public function getcategoriesbyadmin() {
    $categories = $this->model->get();
    return ApiResponseHelper::sendResponse(new Result($categories, 'All categories '));

}

public function deletecategory($id)
{
    $category = $this->model->findOrFail($id);


    $category->delete();

    return ApiResponseHelper::success(null, 'category deleted successfully');
}

public function updatecategoryByAdmin(Request $request, $id)
{
    $category =$this->model->findOrFail($id);

  
    $validator = Validator::make($request->all(), [
        'description' => 'string',
        'name' => 'required|string',
    ]);

    if ($validator->fails()) {
        return ApiResponseHelper::validationError(
            $validator->errors(),
            $validator->errors()->first()
        );
    }

    $data = $validator->validated();

    if (!empty($data)) {
        $category->update($data);
    }

  

    return ApiResponseHelper::sendResponse(new Result($category, 'category updated successfully'));
}





}