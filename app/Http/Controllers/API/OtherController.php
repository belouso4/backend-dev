<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Other\PostsResource;
use App\Http\Resources\Slider\MainSliderResource;
use App\Models\Post;
use App\Models\Slider;
use Illuminate\Http\Request;


class OtherController extends Controller
{
    public function index()
    {
        $slider = Slider::select(['id', 'post_id', 'img', 'order'])
            ->orderBy('order')
            ->with('post:id,excerpt,title,img')
            ->orderBy('order')
            ->get();

        return MainSliderResource::collection($slider);
    }

    public function getPostsByType(Request $request)
    {
        $request->validate([
            'order' => 'required|in:created_at,viewed,random',
        ]);

        $take = $request->query('take') ?? 4;
        $order = $request->query('order') ?? 4;

        switch ($order) {
            case 'created_at':
                $fields = ['id', 'title', 'slug', 'created_at', 'category_id'];
                $posts = Post::select($fields)->take($take)
                    ->orderBy('created_at', 'DESC')
//                    ->with('category:id,name,slug')
                    ->get();
                break;
            case 'viewed':
                $fields = ['id', 'title', 'slug', 'created_at', 'category_id'];
                $posts = Post::select($fields)->withCount('postView')
                    ->orderBy(\DB::raw('post_view_count'),'DESC')
                    ->take($take)
                    ->get();
                break;
            case 'random':
                $fields = ['id', 'title', 'slug', 'created_at', 'category_id', 'excerpt'];
                $posts = Post::select($fields)->inRandomOrder()
                    ->limit($take)
                    ->get();
                break;
        }

        return PostsResource::collection($posts);
    }
}
