<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use Spatie\Permission\Models\Role;
use App\Interfaces\RoleInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
use App\Models\User;
class RoleRepository extends BaseRepositoryImplementation implements RoleInterface
{
    public function model()
    {
        return Role::class;
    }

    public function getAllRolesWithPermissions()
    {
        // Eager load permissions for all roles
        return $this->model->with('permissions')->get();
    }
    // Implement any additional methods here
  public function createRole( $request)
{
    $startTime = microtime(true);
    // dd($request);
    $rules = [
        'name'=>'required',
        'permissions'=>'array'
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
    if(isset($request->permissions))
    if ($r->has('permissions')) {
        $r->syncPermissions($request->permissions);
    }
    return ApiResponseHelper::sendResponse(new Result($r, 'Role created successfully'));
}

public function assignRoleToUser($userId, $role)
{
    $validator = Validator::make(
        ['role' => $role['role']],
        ['role' => 'required|string|exists:roles,name']
    );
    // dd($role['role']);
    // if ($validator->fails()) {
    //     $first = $validator->errors()->first();
    //     return ApiResponseHelper::sendErrorResponse(
    //         new ErrorResult($validator->errors(), $first, null, false, 400),
    //         400
    //     );
    // }
    if ($validator->fails()) {
        return ApiResponseHelper::validationError(
            $validator->errors(),
            $validator->errors()->first()
        );
    }

    $user = User::findOrFail($userId);

    // مهم! بدل assignRole
    $user->syncRoles([$role['role']]);

    return ApiResponseHelper::sendResponse(
        new Result([
            'user' => $user->only(['id','name','email']),
            'assigned_role' => $role['role']
        ], 'Role assigned successfully')
    );
}


public function storeRole( $request)
{
  
    $data = $request->validate([
        'name' => 'required|string|unique:roles',
    ]);

    return response()->json($this->roleRepository->createRole($data), 201);
}
public function getAllRoles()
{
    return $this->all();
}

public function getRoleById($id)
{
    return $this->model->findOrFail($id);
}



public function updateRole($id, array $data)
{
    $role = $this->getRoleById($id);
    $role->update($data);
    return $role;
}

public function deleteRole($id)
{
    $role = $this->getRoleById($id);
    return $role->delete();
}
}