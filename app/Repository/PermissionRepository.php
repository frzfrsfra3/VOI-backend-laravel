<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use Spatie\Permission\Models\Permission;
use App\Interfaces\PermissionInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
class PermissionRepository extends BaseRepositoryImplementation implements PermissionInterface
{
    public function model()
    {
        return Permission::class;
    }

    // Implement any additional methods here
  public function createPermission( $request)
{
    $startTime = microtime(true);
    $rules = [
        'name' => 'required|string|unique:permissions',
    ];

    $validator = Validator::make($request, $rules);

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

    return ApiResponseHelper::sendResponse(new Result($r, 'Permission created successfully'));
}
public function getAllPermissions()
{
    return Permission::all();
}

public function getPermissionById($id)
{
    return Permission::findOrFail($id);
}

// public function createPermission(array $data)
// {
//     return Permission::create($data);
// }

public function updatePermission($id, array $data)
{
    $permission = $this->getPermissionById($id);
    $permission->update($data);
    return $permission;
}

public function deletePermission($id)
{
    $permission = $this->getPermissionById($id);
    return $permission->delete();
}


}