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


Route::group(['prefix' => 'v1'], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    /**
     * Email Routes
     */
//    Route::post('email/verify/{id}',[EmailVerificationController::class, 'verify'])->name('verificationapi.verify');
//    Route::post('email/resend', [EmailVerificationController::class, 'resend']);

    Route::group(['middleware' => 'guest'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
    /**
     * Post Routes
     */
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/post/{post}',[PostController::class, 'show']);
    Route::get('/post/{slug}/comments', [PostCommentsController::class, 'index']);

    Route::group(['middleware' => 'auth:sanctum'], function(){

        Route::get('/user', [UsersController::class, 'show']);

        Route::put('/user/{user}', [UsersController::class, 'update']);
        /**
         * Auth Post Routes
         */
        Route::post('/post/{slug}/like', [PostController::class, 'like']);
        Route::post('/post/{post}/comment', [PostCommentsController::class, 'store']);
        Route::post('/post/comment/{comment}/like', [PostCommentsController::class, 'like']);

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

        /**
         * Admin Post Сomments Routes
         */
        Route::get('/post/{slug}/comments', [AdminPostCommentsController::class, 'index']);
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
        Route::get('/users-roles', [UserRolesController::class, 'index']);
        Route::get('/users/roles', [UserRolesController::class, 'search']);

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
    });


//    Route::post('/companies','API\PostController@store');
//    Route::put('/companies/{company}','API\PostController@update');
//    Route::delete('/companies/{company}','API\PostController@destroy');
});
