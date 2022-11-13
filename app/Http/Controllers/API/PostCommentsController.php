<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store', 'like');
        $this->postCommentRepository = app(IPostComments::class);
        $this->commentRepository = app(IComment::class);
    }

    public function index(Request $request, $slug)
    {
        $comments = $this->postCommentRepository
            ->getCommentsWithUser('slug',$slug, $request->get('offset'));

        if ($comments) {
            return response()->json($comments, 200);
        } else {
            return response()->json($comments, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $comment = new Comment($request->all(['body', 'parent_id']));

        $comment->user()->associate(auth()->user());

        $post->comments()->save($comment);

        if ($comment) {

            return response()->json($comment, 200);

        } else {
            return response()->json($comment, 500);
        }
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
