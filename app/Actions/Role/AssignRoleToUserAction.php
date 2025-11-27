<?php
namespace App\Actions\Role;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repository\RoleRepository;
class AssignRoleToUserAction
{
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function __invoke(Request $request, $userId)
    {
        return $this->roleRepository->assignRoleToUser($userId, $request);
    }
}
