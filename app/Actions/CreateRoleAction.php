<?php
namespace App\Actions;

use App\Repository\RoleRepository;
use Illuminate\Http\Request;

class CreateRoleAction 
{
    protected $RoleService;

    public function __construct(RoleRepository $RoleService) 
    {
        $this->RoleService = $RoleService;
    }

    public function __invoke(Request $request)
    {
        // Implement action functionality
          return $this->RoleService->createRole($data);
    
    }
}