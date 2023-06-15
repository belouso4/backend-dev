<?php

namespace App\Http\Controllers\API\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\AccountResource;
use App\Models\Post;
use Illuminate\Http\Request;

class LikedController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $posts = Post::whereHas('likes', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->withCount('likes')
            ->withCount('userLike')
            ->offset($request['take'] * 10)
            ->limit(10)
            ->get();

        $request->request->add(['exist_like' => 1]);

        return AccountResource::collection($posts);
    }
}
