<?php
namespace App\Actions;

use App\Repository\AdminRepository;
use Illuminate\Http\Request;

class CreateAdminAction 
{
    protected $AdminService;

    public function __construct(AdminRepository $AdminService) 
    {
        $this->AdminService = $AdminService;
    }

    public function __invoke(Request $request)
    {
        // Implement action functionality
          return $this->AdminService->createAdmin($data);
    
    }
}