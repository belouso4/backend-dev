<?php

namespace App\Repositories\Contracts;

interface IPost
{
    public function getAllPosts();
    public function getPostWithTrashedAndTags($id);
    public function restorePost($id);
    public function getDeletedPosts();
    public function forceDelete($id);
    public function whereInWithTrashed($id);

}
