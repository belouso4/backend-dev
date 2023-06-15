<?php

namespace App\Http\Controllers\API\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\CommentedResource;
use Illuminate\Http\Request;

class CommentedController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $select = ['id', 'parent_id', 'body', 'post_id', 'user_id'];

        $comments = $user->comments()
            ->select($select)
            ->with('user:id,avatar,name')
            ->with(['post' => function($query) {
            $query->select(['id', 'slug', 'title', 'excerpt', 'img', 'category_id'])
                ->with('category:id,slug,parent_id');
            }])
            ->offset($request['take'] * 10)
            ->limit(10)
            ->get();

        return CommentedResource::collection($comments);
    }
}
