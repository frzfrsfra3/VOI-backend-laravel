<?php 
namespace App\Actions\Role;

use App\Models\Role;

class RoleGetAction
{
    public function __invoke()
    {
        // Get all roles
        $roles = Role::with('permissions')->get();

        return response()->json([
            'roles' => $roles,
        ]);
    }
}
