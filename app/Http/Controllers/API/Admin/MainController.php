<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Other\PostsRaitingResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function characteristics(Request $request)
    {
        $request->validate([
            'type' => 'required|in:published,comments,users,viewed',
        ]);

        switch ($request->query('type')) {
            case 'published':
                $count = Post::count();
                break;
            case 'comments':
                $count = Comment::count();
                break;
            case 'users':
                $count = User::count();
                break;
            case 'viewed':
                $count = \DB::table('post_views')->count();
                break;
        }

        return response()->json($count);
    }

    public function postRating(Request $request)
    {
        $fields = ['id', 'title', 'slug', 'created_at', 'category_id'];

        $request->validate([
            'type' => 'required|in:viewed,likes',
        ]);

        switch ($request->query('type')) {
            case 'viewed':
                $posts = Post::select($fields)
                    ->withCount('postView')
                    ->orderBy(\DB::raw('post_view_count'),'DESC')
                    ->take(5)
                    ->get();
                break;
            case 'likes':
                $posts = Post::select($fields)
                    ->withCount('likes')
                    ->orderBy(\DB::raw('likes_count'),'DESC')
                    ->take(5)
                    ->get();
                break;
        }

        return PostsRaitingResource::collection($posts);
    }
}
