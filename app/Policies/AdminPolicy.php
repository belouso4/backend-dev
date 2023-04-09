<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if (Auth::user()->roles()->first()->slug === 'super-admin') {
            return true;
        }

        return null;
    }
}
