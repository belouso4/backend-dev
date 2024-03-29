<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class StatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:StatusUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users status update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
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
