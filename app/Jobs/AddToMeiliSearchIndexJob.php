<?php

namespace App\Jobs;

use App\Services\Search\MeiliSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddToMeiliSearchIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $records;
    protected $indexName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($records, $indexName)
    {
        $this->records = $records;
        $this->indexName = $indexName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MeiliSearchService $meiliSearchService)
    {
        $index = $meiliSearchService->getIndex($this->indexName);

        foreach ($this->records as $item) {
            $toSearchableArray = $item->toSearchableArray();

            $modelId = $toSearchableArray['model'].'_'.$item->id;
            $documents[] = array_merge($toSearchableArray, ['modelId' => $modelId]);
        }

        $index->addDocuments($documents);
    }
}
