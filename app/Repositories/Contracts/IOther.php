<?php

namespace App\Repositories\Contracts;

interface IOther
{
    public function getPosts($check);

    public function getPost($id);
}
