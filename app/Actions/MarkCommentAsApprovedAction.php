<?php

namespace App\Actions;

use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class MarkCommentAsApprovedAction
{
    public function __construct(
        protected CommentRepository $comments
    ) {}

    public function __invoke(Request $request, $articleId)
    {
      
            // dd($articleId);
        $comment = $this->comments->MarkAsApproved($articleId, $request);

        if (!$comment) {
            return [
                'error' => true,
                'message' => "Comments disabled for this article",
                'status' => 403
            ];
        }

        return [
            'message' => 'Comment submitted — awaiting approval',
            'comment' => $comment
        ];
    }
}
