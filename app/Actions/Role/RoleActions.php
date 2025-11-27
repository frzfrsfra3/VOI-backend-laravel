<?php

namespace App\Actions;

use App\Repository\RoleRepository;
use Illuminate\Http\Request;

class RoleActions
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        return response()->json($this->roleRepository->getAllRoles(), 200);
    }

    public function getRoleById($id)
    {
        return response()->json($this->roleRepository->getRoleById($id), 200);
    }

    public function createRole(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles',
        ]);

        return response()->json($this->roleRepository->createRole($data), 201);
    }

    public function updateRole(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        return response()->json($this->roleRepository->updateRole($id, $data), 200);
    }

    public function deleteRole($id)
    {
        $this->roleRepository->deleteRole($id);
        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
