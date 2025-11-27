<?php
namespace App\Actions;

use App\Repository\AdminRepository;
use Illuminate\Http\Request;

class GetUsersAction 
{
    protected $AdminService;

    public function __construct(AdminRepository $AdminService) 
    {
        $this->AdminService = $AdminService;
    }

    public function __invoke()
    {
        // Implement action functionality
          return $this->AdminService->getAll();
    
    }
}