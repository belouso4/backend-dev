<?php

namespace App\Repositories\Contracts;

interface IPostComments
{
    public function getCommentsWithUser($column, $value, $offset, $limit);

}
