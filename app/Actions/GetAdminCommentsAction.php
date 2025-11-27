<?php

namespace App\Actions;

use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class GetAdminCommentsAction
{
    public function __construct(
        protected CommentRepository $comments
    ) {}

    public function __invoke( )
    {
      
    
        $comment = $this->comments->getCommentsByAdmin();

        if (!$comment) {
            return [
                'error' => true,
                'message' => "Comments disabled for this article",
                'status' => 403
            ];
        }

       return $comment;
    }
}
