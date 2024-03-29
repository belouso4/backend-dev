<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostWithTitleResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Support\Facades\Gate;


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
            ->paginate(10);

        request()->request->add(['title_main' => $category->name]);

        return (int)request()->query('page') === 1
            ? new PostWithTitleResource($posts)
            : PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        if (Gate::allows('showPostStatusDisabled', $post)) {
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
            $like_my = true;
        } else {
            $post->likes()->detach( auth()->user()->id );
            $like_my = false;
        }

        return response()->json(
            [
                'like_count' => $post->likes()->count(),
                'like_my' => $like_my
            ]
        );
    }

}
