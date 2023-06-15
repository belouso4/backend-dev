<?php

namespace App\Observers;

use App\Jobs\DeletePostFromIndex;
use App\Jobs\UpdatePostInIndex;
use App\Models\Post;

class AdminPostObserver
{
    public function creating(Post $post)
    {
        $this->getAlias($post);
    }

    public function created(Post $post)
    {
        UpdatePostInIndex::dispatch($post);
    }

    public function updated(Post $post)
    {
        if(is_null($post->deleted_at)) {
            UpdatePostInIndex::dispatch($post);
        }
    }

    public function updating(Post $post)
    {
        if ($post->isDirty('title')) {

            $post->slug = \Str::slug($post->title);
            $check = Post::where('slug', '=', $post->slug)->exists();
            if ($check) {
                $post->slug = \Str::slug($post->title) . time();
            }
        }

    }

    public function deleting(Post $post)
    {
        DeletePostFromIndex::dispatch($post->id);
    }

    public function deleted(Post $post)
    {
        $post->update(['status' => '0']);
    }

    public function restored(Post $post)
    {
        //
    }

    public function forceDeleted(Post $post)
    {

    }

    public function getAlias(Post $post)
    {
        if (empty($post->slug)) {
            $post->slug = \Str::slug($post->title);
            $check = Post::where('slug', '=', $post->slug)->exists();
            if ($check) {
                $post->slug = \Str::slug($post->title) . time();
            }
        }
    }
}
