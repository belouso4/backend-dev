<?php

namespace App\Http\Controllers\API;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCommentsResource;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IPostComments;
use Illuminate\Http\Request;

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
        $comment_id = $request->query('comment_id');
        $offset = $request->get('offset');
        $limit = 4;

        if ($comment_id) {
            $position = $post->comments()->where('id', '<=', $comment_id)->count();
            $limit = (Helper::round_up($position / 4, 0)) * 4;
            $offset = 0;
        }

        $comments = $this->postCommentRepository
            ->getCommentsWithUser('slug', $post->slug, $offset, $limit);

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
