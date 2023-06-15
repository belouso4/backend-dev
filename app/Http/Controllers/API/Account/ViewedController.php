<?php

namespace App\Http\Controllers\API\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\AccountResource;
use App\Models\Post;
use Illuminate\Http\Request;

class ViewedController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $posts = Post::whereHas('views', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->offset((int)$request['take'] * 10)
            ->limit(10)
            ->get();

        return AccountResource::collection($posts);
    }
}
