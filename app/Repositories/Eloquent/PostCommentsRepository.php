<?php
namespace App\Repositories\Eloquent;

use App\Models\Post as Model;
use App\Repositories\Contracts\IPostComments;

class PostCommentsRepository extends BaseRepository implements IPostComments
{

    public function model()
    {
        return Model::class;
    }

    public function getCommentsWithUser($column, $value, $offset)
    {
        return $this->model->withTrashed()->where($column, $value)
            ->select(['id'])
            ->withCount('comments')
            ->with(['comments' => function($comments) use ($offset) {
                $comments->withCount(['likes', 'userLike']);
                $comments->with(['replies', 'user:id,name,avatar']);
                $comments->skip($offset)->take(4);
            }])
            ->first();
    }
}

