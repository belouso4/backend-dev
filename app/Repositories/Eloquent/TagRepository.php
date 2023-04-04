<?php
namespace App\Repositories\Eloquent;

use App\Models\Tag as Model;
use App\Repositories\Contracts\ITag;

class TagRepository extends BaseRepository implements ITag {

    public function model()
    {
        return Model::class;
    }

    public function getTags()
    {
        return $this->model
            ->orderBy('id', 'DESC')
            ->paginate();
    }

    public function search($query)
    {
        return $this->model
            ->where('tag', 'like', "%$query%")
            ->paginate();
    }
}
