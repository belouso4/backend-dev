<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostWithTitleResource;
use App\Models\Post;
use Illuminate\Http\Request;

class AllPostsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $posts = Post::with('tags')
            ->withCount('likes')
            ->withCount('userLike')
            ->withCount('postView')
            ->where('status', '=', '1')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return PostResource::collection($posts);
    }
}
