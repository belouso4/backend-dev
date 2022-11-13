<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $posts = Post::with('tags')
            ->withCount('likes')
            ->withCount('userLike')
            ->withCount('postView')
            ->where('status', '=', '1')
            ->orderBy('created_at', 'DESC')
            ->paginate(7);

//        $posts->getCollection()->transform(function($post, $key) {
//            return [
//                'id' => $post->id,
//                'slug' => $post->slug,
//                'created_at' => Carbon::parse($post->created_at)->isoFormat('DD.MM.YYYY'),
//                'title' => $post->title,
//                'desc' => $post->desc,
//                'excerpt' => $post->excerpt,
//                'tags' => $post->tags,
//                'img' => $post->img,
//                'status' => $post->status,
//                'deleted_at' => $post->deleted_at,
//            ];
//        });

        if ($posts) {

            return response()->json($posts, 200);

        } else {
            return response()->json($posts, 500);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
//        $post = Post::where('id', '=', $post->id)->first();
//        if(!isset($post)) {
//            abort(404);
//        }
        $post = Post::where('id', '=', $post->id)
            ->where('status', '=', '1')
            ->withCount('comments')
            ->with(['tags' => function( $query ){
                $query->select(['id', 'tag']);
            }])
            ->first();

        if ($post) {

            if($post->showPost()){// this will test if the user viwed the post or not
                return response()->json($post, 200);
            }

            PostView::createViewLog($post);
            return response()->json($post, 200);

        } else {
            return response()->json($post, 404);
        }
    }

    public function like( $slug ){

        $post = Post::where('slug', '=', $slug)->first();

        if( !$post->likes->contains( Auth::user()->id ) ){

            $post->likes()->attach( Auth::user()->id, [
                'created_at' 	=> date('Y-m-d H:i:s'),
                'updated_at'	=> date('Y-m-d H:i:s')
            ] );
        } else {
            $post->likes()->detach( Auth::user()->id );
            return response(null, 204);
        }

        return response()->json( ['post_liked' => true], 201 );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
