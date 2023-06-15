<?php

namespace App\Services\Search;

use App\Jobs\AddToMeiliSearchIndexJob;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use MeiliSearch\Client;
use Meilisearch\Contracts\IndexesQuery;
use MeiliSearch\Endpoints\Indexes;
use MeiliSearch\Exceptions\TimeOutException;
use Illuminate\Database\Eloquent\Collection;


class MeiliSearchService
{
    public $meiliSearch;

    public function __construct()
    {
        $this->meiliSearch = new Client(
            config('scout.meilisearch.host'),
            config('scout.meilisearch.key')
        );
    }

    public function getIndex($indexName)
    {
        return $this->meiliSearch->getIndex($indexName);
    }

    public function createIndex($indexName): ?array
    {
        try {
            $indexData = $this->meiliSearch->createIndex($indexName);
            return $this->meiliSearch->waitForTask($indexData['taskUid']);
        } catch (TimeOutException $e) {
            return \Log::info($e);
        }
    }

    public function createIndexes($indexes)
    {
        try {
            foreach ($indexes as $index) {
                if ($index == 'admin_global') {
                    $indexData = $this->meiliSearch->createIndex($index, ['primaryKey'=> 'modelId']);
                } else {
                    $indexData = $this->meiliSearch->createIndex($index, ['primaryKey'=> 'id']);
                }

                $taskUid[] = $indexData['taskUid'];
                $indexData = $this->meiliSearch->createIndex($index);
            }

            return $this->meiliSearch->waitForTasks($taskUid);
        } catch (TimeOutException $e) {
            return \Log::info($e);
        }
    }

    public function updateGlobalIndex($indexName)
    {
        $index = $this->getIndex($indexName);
        $index->deleteAllDocuments();

        Post::chunk(5000, function (Collection $posts) use ($indexName) {
            AddToMeiliSearchIndexJob::dispatch($posts, $indexName);
        });

        User::chunk(5000, function (Collection $users) use ($indexName) {
            AddToMeiliSearchIndexJob::dispatch($users, $indexName);
        });

        return $indexName;
    }

    public function updateIndex($indexName, $model, $chunk = 5000)
    {
        $index = $this->getIndex($indexName);
        $index->deleteAllDocuments();

        $model::chunk($chunk, function (Collection $records) use ($indexName) {
            AddToMeiliSearchIndexJob::dispatch($records, $indexName);
        });

//        return $this->meiliSearch->waitForTask($indexStatus['taskUid'], 60000, 500);
        return $indexName;
    }

    public function updatePostInIndex(Post $post)
    {
        $index = $this->getIndex('admin_global');
        $toSearchableArray = $post->toSearchableArray();

        $modelId = $toSearchableArray['model'].'_'.$post->id;
        $document = array_merge($toSearchableArray, ['modelId' => $modelId]);

        $index->updateDocuments([$document]);
    }

    public function updateUserInIndex(User $user)
    {
        $index = $this->getIndex('admin_global');
        $toSearchableArray = $user->toSearchableArray();

        $modelId = $toSearchableArray['model'].'_'.$user->id;
        $document = array_merge($toSearchableArray, ['modelId' => $modelId]);

        $index->updateDocuments([$document]);
    }


    public function indexPosts()
    {
        $index = $this->getIndex('global_admin');
        $posts = Post::all();

        $documents = [];

        foreach ($posts as $post) {
            $document = $post->toSearchableArray();
            $documents[] = $document;
        }

        $index->addDocuments($documents);
    }

    public function indexUsers()
    {
        $index = $this->getIndex('global_admin');
        $users = User::all();

        $documents = [];

        foreach ($users as $user) {
//            $document = [
//                'id' => $post->id,
//                'title' => $post->title,
//                'content' => $post->content,
//                // Добавьте другие поля, которые вы хотите проиндексировать
//            ];
            $document = $user->toSearchableArray();

            $documents[] = $document;
        }

        $index->addDocuments($documents);
    }

    public function deletePostFromIndex($modelId)
    {
        $index = $this->getIndex('admin_global');
        $info = $index->deleteDocument($modelId);
    }

    public function deleteUserFromIndex($modelId)
    {;
        $index = $this->getIndex('admin_global');
        $index->deleteDocument($modelId);
    }

    public function flushIndexes()
    {
        $indexes = $this->meiliSearch->getIndexes()->getResults();

        foreach ($indexes as $index) {
            $index = $this->getIndex($index->getUid());

            $index->deleteAllDocuments();
        }
    }

    public function deleteAllIndexes()
    {
        $indexes = $this->meiliSearch->getIndexes()->getResults();

        foreach ($indexes as $index) {
            $this->meiliSearch->deleteIndex($index->getUid());
        }
    }
}
