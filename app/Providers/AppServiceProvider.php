<?php

namespace App\Providers;

use App\Http\Resources\Admin\Tag\TagResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Observers\AdminCategoryObserver;
use App\Observers\AdminPostObserver;
use App\Observers\AdminRoleObserver;
use App\Observers\AdminTagObserver;
use App\Observers\CommentObserver;
use App\Observers\UserObserver;
use App\Repositories\Contracts\ICategory;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IOther;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Contracts\IPost;
use App\Repositories\Contracts\IPostComments;
use App\Repositories\Contracts\IRole;
use App\Repositories\Contracts\ITag;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IUserRoles;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\OtherRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\PostCommentsRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\UserRolesRepository;
use Illuminate\Support\ServiceProvider;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IPost::class, PostRepository::class);
        $this->app->bind(ITag::class, TagRepository::class);
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IRole::class, RoleRepository::class);
        $this->app->bind(IPermission::class,PermissionRepository::class);
        $this->app->bind(IPostComments::class,PostCommentsRepository::class);
        $this->app->bind(IComment::class,CommentRepository::class);
        $this->app->bind(IUserRoles::class,UserRolesRepository::class);
        $this->app->bind(ICategory::class,CategoryRepository::class);
        $this->app->bind(IOther::class,OtherRepository::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(AdminPostObserver::class);
        Comment::observe(CommentObserver::class);
        Tag::observe(AdminTagObserver::class);
        User::observe(UserObserver::class);
        Role::observe(AdminRoleObserver::class);
        Category::observe(AdminCategoryObserver::class);

        TagResource::withoutWrapping();
    }
}
