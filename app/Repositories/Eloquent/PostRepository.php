<?php
namespace App\Repositories\Eloquent;

use App\Models\Post as Model;
use App\Repositories\Contracts\IPost;

class PostRepository extends BaseRepository implements IPost {

    public function model()
    {
        return Model::class;
    }

    public function getAllPosts() {
        return $this->model
            ->with('category.parent')
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    public function getPostWithTrashedAndTags($id) {
        return $this->model->withTrashed()
            ->where('id', $id)
            ->with('tags')
            ->first();
    }

    public function restorePost($id) {
        $posts = $this->model
            ->onlyTrashed()
            ->whereIn('id', $id)
            ->get();

        foreach ($posts as $post) {
            $post->restore();
        }

        return $posts;
    }

    public function getDeletedPosts() {
        return $this->model
            ->onlyTrashed()
            ->with('category.parent')
            ->orderBy('deleted_at', 'DESC')
            ->paginate();
    }

    public function whereInWithTrashed($id) {
        return $this->model->onlyTrashed()
            ->whereIn('id', $id)
            ->get();
    }

    public function forceDelete($id) {
        $posts = $this->model
            ->onlyTrashed()
            ->whereIn('id', $id)
            ->get();

        foreach ($posts as $post) {
            $post->forceDelete();
        }

        return $posts;
    }

    public function search($query)
    {
        return $this->model
            ->where('title', 'like', "%$query%")
            ->orderBy('created_at', 'DESC')
            ->paginate();
    }
}
