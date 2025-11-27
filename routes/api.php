<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\CreateRatingAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Article\CreateArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Actions\Article\DeleteArticleAction;
use App\Actions\Article\ShowArticleAction;
use App\Actions\Article\MyPostsAction;
use App\Actions\Article\VisibleTodayAction;
use App\Actions\Article\HomePageAction;
use App\Actions\Article\assigncategoryToArticleAction;
use App\Actions\Article\ShowRelatedArticlesAction;
use App\Actions\CreateRoleAction;
use App\Actions\Role\AssignRoleToUserAction;
use App\Actions\Article\RateArticleAction;
use App\Actions\CreateCommentAction;
use App\Actions\GetCommentsAction;
use App\Actions\MarkCommentAsApprovedAction;
use App\Actions\deleteCommentAction;
use App\Actions\GetAdminCommentsAction;
use App\Actions\GetUsersAction;
use App\Actions\SendContactMessageAction;
use App\Actions\MarkAsReviewedAction;

use App\Actions\Admin\getArticlesbyAdmin;
use App\Actions\Admin\UpdateArticleActionByAdmin;

//categories

use App\Actions\getCategoriesbyAdminAction;
use App\Actions\createCategorybyAdminAction;
use App\Actions\UpdateCategoryAction;
use App\Actions\deleteCategoryAction;
use App\Actions\createCategoryAction;
// use App\Actions\getArticlesbyAdmin;

use App\Actions\getContactMessagesAction;
/*th
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/contact', SendContactMessageAction::class);
Route::post('/ratings', CreateRatingAction::class);
Route::get('/admin/categories', getCategoriesbyAdminAction::class); 
Route::get('/articles/{id}/related', ShowRelatedArticlesAction::class);

Route::post('/register', RegisterAction::class)->name('register');
Route::post('/login', LoginAction::class)->name('login');
Route::get('/articles/{id}', ShowArticleAction::class);
Route::post('/logout', LogoutAction::class)->middleware('auth:api')->name('logout');
Route::get('/posts', VisibleTodayAction::class);
Route::get('/articleshomepage',HomePageAction::class);
Route::get('/users', GetUsersAction::class);
Route::get('/comments/{id}', GetCommentsAction::class); 
Route::middleware('auth:api')->group(function () {
    Route::get('/my-posts', MyPostsAction::class);
 
    
    Route::post('/articles', CreateArticleAction::class);
   
    Route::put('/articles/{id}', UpdateArticleAction::class);
    Route::delete('/articles/{id}', DeleteArticleAction::class);
    Route::post('/comments/{id}', CreateCommentAction::class); 
    Route::post('/articles/{id}/rate', RateArticleAction::class);
});

Route::middleware(['auth:api', 'role:admin' ])->group(function () {
    Route::post('/admin/contact/{id}/mark',MarkAsReviewedAction::class);
    Route::get('/admin/contact-messages',getContactMessagesAction::class);
    // Route::get('/admin/categories', getCategoriesbyAdminAction::class); 
    Route::post('/admin/categories', createCategorybyAdminAction::class); 
    Route::delete('/admin/categories/{id}', deleteCategoryAction::class);
    Route::put('/admin/categories/{id}', UpdateCategoryAction::class);
    Route::get('/admin/articles', getArticlesbyAdmin::class); 
    Route::put('/admin/articles/{id}', UpdateArticleActionByAdmin::class);
    Route::delete('/admin/articles/{id}', DeleteArticleAction::class);
    Route::post('/admin/articles/assigntocategory/{id}',assigncategoryToArticleAction::class);
    Route::post('/admin/comments/{id}/approve',MarkCommentAsApprovedAction::class);
    Route::get('/admin/comments', GetAdminCommentsAction::class); 
    Route::delete('/admin/comments/{id}', deleteCommentAction::class);
    Route::post('/admin/articles', CreateArticleAction::class);
    Route::get('/admin/users', GetUsersAction::class);
    Route::post('/admin/users/{userId}/assign-role', AssignRoleToUserAction::class);
    Route::get('/admin/roles', [RoleActions::class, 'getAllRoles']);
    Route::get('/admin/roles/{id}', [RoleActions::class, 'getRoleById']);
    Route::post('/admin/roles', CreateRoleAction::class);
    Route::put('/admin/roles/{id}', [RoleActions::class, 'updateRole']);
    Route::delete('/admin/roles/{id}', [RoleActions::class, 'deleteRole']);
    

});