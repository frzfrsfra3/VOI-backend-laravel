<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\Models\User;
use App\Interfaces\AdminInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
class AdminRepository extends BaseRepositoryImplementation implements AdminInterface
{
    public function model()
    {
        return User::class;
    }


     // Implement any additional methods here
     public function getProfileByUserId($userId)
     {
         return $this->model->with('user')->where('user_id', $userId)->first();
     }
 
     public function getProfileByAuth()
     {
         return $this->model->where('user_id', auth()->user()->id)->first();
     }
 
 
     
     // Implement any additional methods here
     public function getAll()
     {
        $users=$this->model->with('roles')->paginate(10);
        // $pagination = [
        //     'total' => $panels->total(),
        //     'current_page' => $panels->currentPage(),
        //     'last_page' => $panels->lastPage(),
        //     'per_page' => $panels->perPage(),
        // ];
        
            return $users;

        // return ApiResponseHelper::sendResponseWithPagination(
        //     new Result(
        //         $panels->items(),
        //         'Users fetched successfully.',
        //         $pagination
        //     )

        // );
       
     }
 
   
     // ✏️ Create or update profile
     public function saveProfile(Request $data)
     {
             $data=$data->toArray();
         return $this->model->updateOrCreate(
             ['user_id' => $data['user_id']],
             $data
         );
     }
 
    // Implement any additional methods here
  public function createAdmin(Request $request)
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

    return ApiResponseHelper::sendResponse(new Result($r, 'Admin created successfully'));
}
}