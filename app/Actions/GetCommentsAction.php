<?php

namespace App\Actions;

use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class GetCommentsAction
{
    public function __construct(
        protected CommentRepository $comments
    ) {}

    public function __invoke( $articleId)
    {
      
    
        $comment = $this->comments->getCommentsByArticles($articleId);

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
