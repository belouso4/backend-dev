<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
          Post::class => PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
//        Gate::before(function ($user) {
//            return $user->hasRole('super-admin') ? true : null;
//        });

        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('FRONTEND_URL') . '/reset-password?token=' . $token;
        });
//        Passport::routes();

        Gate::define('showPostStatusDisabled', function (User $user, Post $post) {
            if (!($user->roles()->exists() && $user->can('edit', $post))
                && $post->status != 1) {
                return true;
            }
            return false;
        });
    }
}
