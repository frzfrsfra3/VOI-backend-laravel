<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\Models\Article;
use App\Models\ArticleVisibilityDay;
use App\Models\Rating;
use App\Interfaces\ArticleInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
use Carbon\Carbon;

class ArticleRepository extends BaseRepositoryImplementation implements ArticleInterface
{
    public function model()
    {
        return Article::class;
    }

    public function createArticle(Request $request)
    {
        // 1) التحقق من المدخلات
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visible_days' => 'required|array|min:1',
            'visible_days.*' => 'integer|between:0,6',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
        ]);
    
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }
    
        $data = $validator->validated();
        $data['author_id'] = Auth::id();
    
        // 2) توليد slug فريد
        $baseSlug = \Str::slug($data['title']);
        $slug = $baseSlug;
        $counter = 1;
    
        while ($this->model->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
    
        $data['slug'] = $slug;
    
        // 3) رفع الصورة إن وُجدت
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path; // تأكد أن لديك العمود image في الجدول
        }
    
        // 4) إنشاء المقال
        $article = $this->model->create($data);
    
        // 5) حفظ أيام الظهور
        foreach ($data['visible_days'] as $day) {
            ArticleVisibilityDay::create([
                'article_id' => $article->id,
                'day_of_week' => $day,
            ]);
        }
    
        // 6) إرجاع المقال مع العلاقات
        $article->load(['author', 'visibilityDays', 'ratings']);
    
        return ApiResponseHelper::sendResponse(
            new Result($article, 'Article created successfully')
        );
    }
    

     public function search($filters)
    {
        $query = $this->model->published()->with(['author','categories'])->latest();

        if (!empty($filters['q'])) {
            $query->where('title', 'like', '%'.$filters['q'].'%');
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        return $query->paginate(10)->withQueryString();
    }

    public function findBySlug($slug)
    {
        return $this->model
            ->with(['author','categories','comments'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    // public function related($article)
    // {
    //     return $this->model
    //         ->published()
    //         ->whereHas('categories', function ($q) use ($article) {
    //             $q->whereIn('categories.id', $article->categories->pluck('id'));
    //         })
    //         ->where('id', '!=', $article->id)
    //         ->limit(4)
    //         ->get();
    // }
    public function related($id)
    {
        $article = Article::findOrFail($id);
        $relatedarticles= $this->model
            // ->published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->limit(4)
            ->get();
            return ApiResponseHelper::sendResponse(new Result($relatedarticles, 'Articles visible today retrieved'));
  
    }
    
    public function updateArticle(Request $request, $id)
    {
        $article =$this->model->findOrFail($id);
    
        if ($article->author_id !== Auth::id()) {
            return ApiResponseHelper::error(
                'Unauthorized',
                ApiResponseCodes::UNAUTHORIZED,
                null,
                403
            );
        }
    
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'visible_days' => 'sometimes|array|min:1',
            'visible_days.*' => 'integer|between:0,6',
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
        ]);
      
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }
    
        $data = $validator->validated();
       
        if (isset($data['image'])) {
            $path = $request->file('image')->store('articles', 'public');
            dd($path);
            $data['image'] = $path; // تأكد أن لديك العمود image في الجدول
        }
        // dd($request->image);
        if (!empty($data)) {
            $article->update($data);
        }
    
        if (isset($data['visible_days'])) {
            ArticleVisibilityDay::where('article_id', $article->id)->delete();
            foreach ($data['visible_days'] as $day) {
                ArticleVisibilityDay::create([
                    'article_id' => $article->id,
                    'day_of_week' => $day,
                ]);
            }
        }
    
        $article->load(['author', 'visibilityDays', 'ratings']);
    
        return ApiResponseHelper::sendResponse(new Result($article, 'Article updated successfully'));
    }
    public function updateArticleByAdmin(Request $request, $id)
    {
        $article =$this->model->findOrFail($id);
    
      
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'visible_days' => 'sometimes|array|min:1',
            'visible_days.*' => 'integer|between:0,6',
            'category_id'=>'exists:categories,id',
            'is_published'=>'integer',
            'comments_enabled'=>'integer'
        ]);
    
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }
    
        $data = $validator->validated();
    
        if (!empty($data)) {
            $article->update($data);
        }
    
        if (isset($data['visible_days'])) {
            ArticleVisibilityDay::where('article_id', $article->id)->delete();
            foreach ($data['visible_days'] as $day) {
                ArticleVisibilityDay::create([
                    'article_id' => $article->id,
                    'day_of_week' => $day,
                ]);
            }
        }
    
        $article->load(['author', 'visibilityDays', 'ratings']);
    
        return ApiResponseHelper::sendResponse(new Result($article, 'Article updated successfully'));
    }
   
    public function assigncategoryToArticle($categoryId, $articleId)
{
    $validator = Validator::make(
        // ['role' => $articleId['role']],
        ['category_id' => 'required|exists:categories,name']
    );
    // dd($role['role']);
    // if ($validator->fails()) {
    //     $first = $validator->errors()->first();
    //     return ApiResponseHelper::sendErrorResponse(
    //         new ErrorResult($validator->errors(), $first, null, false, 400),
    //         400
    //     );
    // }
    // $articleId
    if ($validator->fails()) {
        return ApiResponseHelper::validationError(
            $validator->errors(),
            $validator->errors()->first()
        );
    }

    $category = Category::findOrFail($categoryId);

   if($category){
    $article=$this->model->findOrFail($articleId);
    $article['category_id']=$categoryId;
    $article->save();
   }

    return ApiResponseHelper::sendResponse(
        new Result([
            'article' => $article,
            'destination_category' => $article['category_id']
        ], 'Article has been assigned successfully')
    );
}


    public function deleteArticle($id)
    {
        $article = $this->model->findOrFail($id);

        if ($article->author_id !== Auth::id()) {
            return ApiResponseHelper::error(
                'Unauthorized',
                ApiResponseCodes::UNAUTHORIZED,
                null,
                403
            );
        }

        $article->delete();

        return ApiResponseHelper::success(null, 'Article deleted successfully');
    }

    public function getArticle($id)
    {
        $article = $this->model ->published()->with('ratings')->findOrFail($id);
        $article->average_rating = $article->ratings()->avg('rating');

        return ApiResponseHelper::sendResponse(new Result($article, 'Article retrieved'));
    }

    public function getMyArticles()
    {
        $articles = Auth::user()->articles()->with('visibilityDays')->get();
        return ApiResponseHelper::sendResponse(new Result($articles, 'My articles retrieved'));
    }

    public function getVisibleTodayArticles($request = null)
    {
        $today = Carbon::now()->dayOfWeek;
        
        $query = $this->model->whereHas('visibilityDays', fn ($q) =>
            $q->where('day_of_week', $today)
        )->with(['author', 'ratings']);
    
        if ($request && $request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('content', 'like', '%'.$request->search.'%');
            });
        }
    
        $articles = $query->get();
        
        return ApiResponseHelper::sendResponse(new Result($articles, 'Articles visible today retrieved'));
    }
    public function getArticlesbyadmin() {
        $articles = $this->model->get();
        return ApiResponseHelper::sendResponse(new Result($articles, 'All Articles '));
   
    }

    public function rateArticle(Request $request, $id)
    {
        $article = $this->model->findOrFail($id);

        if ($article->author_id === Auth::id()) {
            return ApiResponseHelper::error(
                'You cannot rate your own article',
                ApiResponseCodes::INVALID_REQUEST,
                null,
                403
            );
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5'
        ]);

        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }

        $rating = Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'article_id' => $id],
            ['rating' => $request->rating]
        );

        return ApiResponseHelper::sendResponse(new Result($rating, 'Rating submitted successfully'));
    }

    // في ArticleRepository.php

public function getHomepageArticles($filters = [])
{
    $query = $this->model
        ->published()
        ->with(['author', 'categories', 'visibilityDays'])
        // ->where('published',1)
        ->latest();

    // البحث
    if (!empty($filters['search'])) {
        $query->where(function($q) use ($filters) {
            $q->where('title', 'like', '%'.$filters['search'].'%')
              ->orWhere('content', 'like', '%'.$filters['search'].'%');
        });
    }
    // if ($request && $request->has('search')) {
    //     $query->where(function($q) use ($request) {
    //         $q->where('title', 'like', '%'.$request->search.'%')
    //           ->orWhere('content', 'like', '%'.$request->search.'%');
    //     });
    // }

    // التصنيفات
    if (!empty($filters['category'])) {
        // $query->whereHas('categories', function ($q) use ($filters) {
        //     $q->where('slug', $filters['category'])
        //       ->orWhere('id', $filters['category']);
        // });
        $query->where('category_id',$filters['category']);
    }

    // الترتيب
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'latest':
                // $query->latest();
                $query->orderBy('updated_at','desc');
                break;
            case 'oldest':
                $query->orderBy('updated_at','asc');
                break;
            case 'popular':
                // إذا كان لديك نظام مشاهدات أو تقييمات
                $query->withCount('ratings')->orderBy('ratings_count', 'desc');
                break;
        }
    }

    $articles= $query->paginate($filters['per_page'] ?? 5);
     return ApiResponseHelper::sendResponse(new Result($articles, 'Articles visible today retrieved'));
  
}

}
