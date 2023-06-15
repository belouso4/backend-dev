<?php

namespace App\Console\Commands\MeiliSearch;

use App\Services\Search\MeiliSearchService;
use Illuminate\Console\Command;

class DeleteAllIndexesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meiliSearch:delete-all-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all indexes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(MeiliSearchService $meiliSearchService)
    {
        try {
            $meiliSearchService->deleteAllIndexes();
            $this->info('All indexes deleted successfully.');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
