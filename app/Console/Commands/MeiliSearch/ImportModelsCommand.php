<?php

namespace App\Console\Commands\MeiliSearch;

use App\Models\Post;
use App\Services\Search\MeiliSearchService;
use Illuminate\Console\Command;

class ImportModelsCommand extends Command
{
    protected $signature = 'meiliSearch:import';

    protected $description = 'Import models into Meilisearch index';

    protected $indexes = [
        'admin_global',
        'posts',
//        'admin_posts',
//        'admin_users',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(MeiliSearchService $meiliSearchService)
    {
        if ($meiliSearchService->createIndexes($this->indexes)) {
            $this->info('Indexes created');

            $adminGlobal = $meiliSearchService->updateGlobalIndex('admin_global');
            $this->info('Data added to the index: '.$adminGlobal);

            $posts = $meiliSearchService->updateIndex('posts', Post::class);
            $this->info('Data added to the index: '.$posts);

//            $adminPosts = $meiliSearchService->updateIndex('admin_posts', Post::class);
//            $this->info('Data added to the index: '.$adminPosts);
//
//            $adminUsers = $meiliSearchService->updateIndex('admin_users', User::class, 1000);
//            $this->info('Data added to the index: '.$adminUsers);
        }

        return 0;
    }
}
