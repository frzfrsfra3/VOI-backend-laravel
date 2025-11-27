<?php
namespace App\Actions;

use App\Repository\ContactRepository;
use Illuminate\Http\Request;

class CreateContactAction 
{
    protected $ContactService;

    public function __construct(ContactRepository $ContactService) 
    {
        $this->ContactService = $ContactService;
    }

    public function __invoke(Request $request)
    {
        // Implement action functionality
          return $this->ContactService->createContact($data);
    
    }
}