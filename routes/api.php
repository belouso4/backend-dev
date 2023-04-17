<?php

use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\PostController as PostController;
use App\Http\Controllers\API\Admin\PostController as AdminPostController;
use App\Http\Controllers\API\Admin\TagsController;
use App\Http\Controllers\API\PostCommentsController;
use App\Http\Controllers\API\Admin\PostCommentsController as AdminPostCommentsController;
use App\Http\Controllers\API\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\API\Admin\RolesController;
use App\Http\Controllers\API\Admin\UserRolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\GeneralController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\API\CategoryController;


Route::group(['prefix' => 'v1'], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    /**
     * Email Routes
     */
    Route::post('email/verify/{id}',[EmailVerificationController::class, 'verify'])->name('verificationapi.verify');
    Route::post('email/resend', [EmailVerificationController::class, 'resend']);

    Route::group(['middleware' => 'guest'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset']);
    });

    /**
     * Post Routes
     */
    Route::get('/category/{category}/articles', [PostController::class, 'index']);
    Route::get('/article/{post}', [PostController::class, 'show']);
    Route::get('/article/{post}/comments', [PostCommentsController::class, 'index']);

    /**
     * Category Routes
     */
    Route::get('/categories', CategoryController::class);

    Route::group(['middleware' => ['auth:sanctum']], function(){
        Route::get('/user', [UsersController::class, 'show']);
        Route::put('/user/{user}', [UsersController::class, 'update'])->middleware(['verified']);

        /**
         * Auth Post Routes
         */
        Route::post('/article/{post:id}/like', [PostController::class, 'like']);
        Route::post('/article/{post}/comment', [PostCommentsController::class, 'store']);
        Route::post('/article/comment/{comment}/like', [PostCommentsController::class, 'like']);

    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum','isAdmin']], function () {

        /**
         * Admin Post Routes
         */
        Route::get('/posts', [AdminPostController::class, 'index']);
        Route::post('/post/create', [AdminPostController::class, 'store']);
        Route::get('/post/{post}/edit', [AdminPostController::class, 'edit'])->withTrashed();
        Route::put('/post/update/{post}', [AdminPostController::class, 'update']);
        Route::delete('/post/delete/{id}', [AdminPostController::class, 'destroy']);
        Route::put('/post/restore/{id}', [AdminPostController::class, 'restore']);
        Route::get('/posts/trashed', [AdminPostController::class, 'getDeletedPosts']);
        Route::delete('/posts/clear-trashed/{id}', [AdminPostController::class, 'forceDelete']);
        Route::get('/posts/search', [AdminPostController::class, 'search']);

        /**
         * Admin Post Сomments Routes
         */
        Route::get('/post/{post}/comments', [AdminPostCommentsController::class, 'index']);
        Route::post('/post/{post}/comment', [AdminPostCommentsController::class, 'store']);
        Route::delete('/post/comment/{comment}/delete', [AdminPostCommentsController::class, 'destroy']);
        Route::post('/post/comment/{comment}/like', [AdminPostCommentsController::class, 'like']);

        /**
         * Admin Tag Routes
         */
        Route::post('/tag/create', [TagsController::class, 'store']);
        Route::get('/tags', [TagsController::class, 'index']);
        Route::get('/tags/search', [TagsController::class, 'search']);
        Route::put('/tag/update/{tag}', [TagsController::class, 'update']);
        Route::delete('/tag/delete/{id}', [TagsController::class, 'delete']);

        /**
         * Admin Category Routes
         */
        Route::get('/categories', [AdminCategoryController::class, 'index']);
        Route::post('/category/create', [AdminCategoryController::class, 'store']);
        Route::put('/categories/update', [AdminCategoryController::class, 'updateMenu']);
        Route::put('/category/update/{category}', [AdminCategoryController::class, 'update']);
        Route::delete('/category/delete/{category}', [AdminCategoryController::class, 'destroy']);

        /**
         * Admin Users Routes
         */
        Route::get('/users', [AdminUsersController::class, 'index']);
        Route::post('/user', [AdminUsersController::class, 'store']);
        Route::get('/user/edit/{user}', [AdminUsersController::class, 'edit']);
        Route::put('/user/{user}', [AdminUsersController::class, 'update']);
        Route::delete('/user/delete/{user}', [AdminUsersController::class, 'destroy']);
        Route::get('/user/search', [AdminUsersController::class, 'search']);

        /**
         * Admin Users with Roles Routes
         */
        Route::get('/users/roles', [UserRolesController::class, 'index']);
        Route::get('/users/roles/search', [UserRolesController::class, 'search']);

        /**
         * Admin Roles and Permission Routes
         */
        Route::get('/roles', [RolesController::class, 'index']);
        Route::post('/role', [RolesController::class, 'store']);
        Route::get('/role/{role}', [RolesController::class, 'edit']);
        Route::get('/permissions', [RolesController::class, 'permissions']);
        Route::put('/role/{role}/permission', [RolesController::class, 'permissionUpdate']);
        Route::get('/role/{role}/permissions-default', [RolesController::class, 'setDefaultPermissions']);
        Route::get('/role/{role}/permissions-minimum', [RolesController::class, 'setMinimumPermissions']);
        Route::put('/role/{role}', [RolesController::class, 'update']);
        Route::delete('/role/{role}', [RolesController::class, 'destroy']);
//        Route::get('/role/{role}/users', [RolesController::class, 'users']);
        Route::get('/roles/search', [RolesController::class, 'search']);

        /**
         * Admin General Routes
         */
        Route::get('/search', [GeneralController::class, 'search']);
        Route::put('/profile', [GeneralController::class, 'profile']);

        /**
         * Admin General Routes
         */
        Route::post('/mail', [\App\Http\Controllers\API\Admin\MailController::class, 'store']);
        Route::get('/mail/search', [\App\Http\Controllers\API\Admin\MailController::class, 'search']);
    });



//    Route::post('/companies','API\PostController@store');
//    Route::put('/companies/{company}','API\PostController@update');
//    Route::delete('/companies/{company}','API\PostController@destroy');
});
