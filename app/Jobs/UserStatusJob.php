<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle()
    {
        $users =  User::whereNotNull('banned_until')->get();

        foreach ($users as $user) {
            $banned_days = now()->diffInMinutes($user->banned_until);

            if ($banned_days === 0) {
                User::where('id', $user->id)->update(['banned_until' => null]);
            }

            \Log::info($banned_days);
        }
    }
}
