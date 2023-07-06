<?php

namespace App\Repositories\Eloquent;

use App\Models\Slider as Model;
use App\Repositories\Contracts\IOther;
class OtherRepository extends BaseRepository implements IOther
{
    public function model()
    {
        return Model::class;
    }

    public function getPosts($check)
    {
        return $this->model->query()
            ->when($check, function ($q) {
                return $q->take(5);
            })
            ->with('post:id,excerpt,title,img')
            ->orderBy('order')
            ->get();
    }

    public function getPost($id)
    {
        return $this->model
            ->select(['id', 'post_id', 'img', 'order'])
            ->where('id',$id)
            ->with('post:id,excerpt,title,img')
            ->first();
    }
}
