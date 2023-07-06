<?php

namespace App\Repositories\Contracts;

interface ICategory
{
    public function getCategories($query);

    public function getCategoriesWhereParentIdNull();
}
