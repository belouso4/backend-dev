<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCommentsResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\IPostComments;
use Illuminate\Http\Request;

class PostCommentsController extends AdminController
{
    protected $comment;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:viewAny,'.Post::class);
//        $this->middleware('throttle:public');
        $this->comment = app(IPostComments::class);
    }

    public function index(Request $request, $slug)
    {
        $comments = $this->comment
            ->getCommentsWithUser(
                'slug',
                $slug,
                $request->query('offset')
            );

        return new PostCommentsResource($comments);
    }

    public function create()
    {
        //
    }

    public function store(CommentCreateRequest $request, Post $post)
    {
        $comment = new Comment($request->all(['body', 'parent_id']));

        $comment->user()->associate(auth()->user());
        $post->comments()->save($comment);

        return new CommentResource($comment);
    }

    public function like(Comment $comment)
    {
        $user_id = auth()->id();

        if($comment->isLikedByUser($user_id)){
            $comment->likes()->detach($user_id);
        } else {
            $comment->likes()->attach($user_id);
        }

        return response()->json( $comment->likes()->count(), 201 );
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        $comment->deleteWithReplies();
        return response()->json(null. 204);
    }
}
