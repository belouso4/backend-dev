<?php

namespace App\Console\Commands\MeiliSearch;

use App\Services\Search\MeiliSearchService;
use Illuminate\Console\Command;

class FlushModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meiliSearch:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the index of the the given searchable';

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
    public function handle(MeiliSearchService $meiliSearchService)
    {
        try {
            $meiliSearchService->flushIndexes();
            $this->info('All records have been flushed.');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
