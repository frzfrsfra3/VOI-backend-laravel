<?php
namespace App\Actions;

use App\Repository\PermissionRepository;
use Illuminate\Http\Request;

class CreatePermissionAction 
{
    protected $PermissionService;

    public function __construct(PermissionRepository $PermissionService) 
    {
        $this->PermissionService = $PermissionService;
    }

    public function __invoke(Request $request)
    {
        // Implement action functionality
          return $this->PermissionService->createPermission($data);
    
    }
}