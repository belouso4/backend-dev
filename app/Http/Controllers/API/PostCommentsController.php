<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCommentsResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IPostComments;
use Illuminate\Http\Request;
use Auth;

class PostCommentsController extends Controller
{

    protected $postCommentRepository;
    protected $commentRepository;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store', 'like');
        $this->postCommentRepository = app(IPostComments::class);
        $this->commentRepository = app(IComment::class);
    }

    public function index(Request $request, Post $post)
    {
        $comments = $this->postCommentRepository
            ->getCommentsWithUser('slug',$post->slug, $request->get('offset'));

        return new PostCommentsResource($comments);
    }

    public function store(Request $request, Post $post)
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
}
