<?php
namespace App\Actions;

use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class deleteCommentAction 
{
    protected $CommentService;

    public function __construct(CommentRepository $CommentService) 
    {
        $this->CommentService = $CommentService;
    }

    public function __invoke($id)
    {
        // Implement action functionality
          return $this->CommentService->deleteComment($id);
    
    }
}