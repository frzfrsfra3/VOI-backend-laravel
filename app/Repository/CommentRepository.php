<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\Models\Comment;
use App\Interfaces\CommentInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\ApiHelper\ErrorResult;
class CommentRepository extends BaseRepositoryImplementation implements CommentInterface
{
    public function model()
    {
        return Comment::class;
    }

    public function createForArticle($articleId, $data)
    {
        $article = Article::findOrFail($articleId);
    
        if (!$article->comments_enabled) {
            return null;
        }
    
        $userId = auth()->id();
    
        // 1) تحقق يدوي قبل الإنشاء
        $existing = $article->comments()
            ->where('user_id', $userId)
            ->first();
    
        if ($existing) {
            return [
                'exists' => true,
                'message' => 'لقد قمت بإضافة تعليق مسبقاً وهو بانتظار الموافقة.'
            ];
        }
    
        try {
            return $article->comments()->create([
                'body'     => $data['body'],
                'user_id'  => $userId,
                'approved' => false,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
    
            // 2) في حال MySQL رمى duplicate error
            if ($e->errorInfo[1] == 1062) {
                return [
                    'exists' => true,
                    'message' => 'لقد قمت بإضافة تعليق مسبقاً وهو بانتظار الموافقة.'
                ];
            }
    
            throw $e; // لو خطأ مختلف خليه يطلع
        }
    }
    public function MarkAsApproved($id,$data) 
    {
        $validator = Validator::make(
            ['approved' => $data['approved']],
            ['approved' => 'required|int']
        );
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }
        $comment=$this->model->findOrFail($id);
       $comment['approved']=1;
      
        $comment->save();
        // dd($comment);
        return ApiResponseHelper::sendResponse(
            new Result([
                'user' => $comment->only(['id','name','email','status']),
                
            ], 'comment marked as approved!')
        );

    }


    public function deleteComment($id)
    {
        $article = $this->model->findOrFail($id);

       

        $article->delete();

        return ApiResponseHelper::success(null, 'Comment deleted successfully');
    }

    public function getCommentsByArticles($articleId)
    {
        $comments=$this->model->with('user')->where('article_id',$articleId)->where('approved',1)->paginate();
        return $comments;

    }

    public function getCommentsByAdmin()
    {
        $comments=$this->model->with(['user','article'])->paginate();
        return $comments;

    }
    // Implement any additional methods here
  public function createComment(Request $request)
{
    $startTime = microtime(true);
    $rules = [
  
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        // Extract the first validation message
        $firstErrorMessage = $validator->errors()->first();

        return ApiResponseHelper::sendErrorResponse(
            new ErrorResult(
                $validator->errors(),
                $firstErrorMessage, // Use the first validation message here
                null,
                false,
                400
            ),
            400
        );
    }

    $r = $this->create($validator->validated());

    return ApiResponseHelper::sendResponse(new Result($r, 'Comment created successfully'));
}
}