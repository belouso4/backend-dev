<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class OtherController extends Controller
{
    public function index()
    {
        $response = Redis::get('main-slide');
        $sliders = json_decode($response) ?? [];

//        $sliders = Post::whereIn('id', json_decode($response))
//            ->withCount('userLike')
//            ->withCount('postView')
//            ->where('status', '=', '1')
//            ->with('tags')
//            ->withCount('likes')
//            ->get();


//        return PostResource::collection($sliders);
        return $sliders;
    }
}
