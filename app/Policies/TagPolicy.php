<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy extends AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->checkPermission('tag.view');
    }

    public function create(User $user)
    {
        return $user->checkPermission('tag.create');
    }

    public function update(User $user)
    {
        return $user->checkPermission('tag.edit');
    }

    public function delete(User $user)
    {
        return $user->checkPermission('tag.delete');
    }
}
