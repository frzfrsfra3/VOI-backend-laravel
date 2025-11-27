<?php

namespace App\Actions;

use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class CreateCommentAction
{
    public function __construct(
        protected CommentRepository $comments
    ) {}

    public function __invoke(Request $request, $articleId)
    {
        $validated = $request->validate([
            // 'author_name'  => 'required|max:255',
            // 'author_email' => 'required|email',
            'body'         => 'required|min:5'
        ]);

        $comment = $this->comments->createForArticle($articleId, $validated);

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
