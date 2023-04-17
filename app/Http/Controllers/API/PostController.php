<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;


class PostController extends Controller
{
    public function index(Category $category)
    {
        $posts = $category->posts()->with('tags')
            ->withCount('likes')
            ->withCount('userLike')
            ->withCount('postView')
            ->where('status', '=', '1')
            ->orderBy('created_at', 'DESC')
            ->paginate(7);

        return PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        $permission = auth()->user() && auth()->user()->can('edit', $post);
        if (!$permission && $post->status != 1) {
            abort(404);
        }

        $posts = Post::where('id', $post->id)
            ->withCount('comments')
            ->with('tags:id,tag')
            ->first();

        if(!$post->showPost()){// this will test if the user viwed the post or not
            PostView::createViewLog($post);
        }

        return new PostResource($post);
    }

    public function like( Post $post){
        if( !$post->likes->contains( auth()->user()->id ) ){
            $post->likes()->attach(auth()->user()->id);
        } else {
            $post->likes()->detach( auth()->user()->id );
        }

        return response()->json( $post->likes()->count() );
    }

}
