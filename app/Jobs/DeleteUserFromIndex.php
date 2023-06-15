<?php

namespace App\Jobs;

use App\Services\Search\MeiliSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteUserFromIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(MeiliSearchService $meiliSearchService)
    {
        $meiliSearchService->deleteUserFromIndex('user_'.$this->id);
    }
}
