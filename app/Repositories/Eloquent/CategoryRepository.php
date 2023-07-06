<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Contracts\ICategory;

class CategoryRepository extends BaseRepository implements ICategory
{
    public function model()
    {
        return Model::class;
    }

    public function getCategories($query)
    {
        return $this->model->query()->when(!$query, function ($q) {
            return $q->orderBy('order')
                ->whereNull('parent_id')
                ->with('children');
        })->get();
    }


    public function getCategoriesWhereParentIdNull()
    {
        return $this->model->where('parent_id', null)
            ->orderBy('order')
            ->with('children')
            ->get();
    }
}
