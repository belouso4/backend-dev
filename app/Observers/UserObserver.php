<?php

namespace App\Observers;

use App\Jobs\DeleteUserFromIndex;
use App\Jobs\UpdateUserInIndex;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        if(!$user->isDirty('avatar') || $user->avatar === null) {
            $user->avatar = 'avatar.png';
        }
    }
    public function created(User $user)
    {
        UpdateUserInIndex::dispatch($user);
    }

    public function updated(User $user)
    {
        if($user->isDirty('password')) {
            request()->session()->regenerateToken();
        }

        UpdateUserInIndex::dispatch($user);
    }

    public function deleting(User $user)
    {
        DeleteUserFromIndex::dispatch($user->id);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
