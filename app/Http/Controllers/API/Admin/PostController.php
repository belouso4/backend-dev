<?php

namespace App\Http\Controllers\API\Admin;

use App\Traits\ImageUploadTrait;
use App\Http\Requests\AdminPostCreateRequest;
use App\Http\Requests\AdminPostUpdateRequest;
use App\Http\Resources\Admin\Post\PostResource;
use App\Models\Post;
use App\Repositories\Contracts\IPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends AdminController
{
    use ImageUploadTrait;

    protected $postRepository;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:viewAny,App\Models\Post');
        $this->middleware('can:edit,post')->only(['edit', 'update']);
        $this->middleware('can:create,App\Models\Post')->only(['store']);
        $this->middleware('can:delete,App\Models\Post')->only(['forceDelete', 'getDeletedPosts', 'restore', 'destroy']);
        $this->postRepository = app(IPost::class);
    }

    public function index()
    {
        $posts = $this->postRepository->getAllPosts();

        return PostResource::collection($posts);
    }

    public function create(Request $request)
    {
        //
    }

    public function store(AdminPostCreateRequest $request)
    {
        $post = new Post($request->all());
        $this->setImage($request->file('img'), '/posts');
        $post->img = $this->uploadImage();

        $post->save();
        $post->tags()->attach($request['tags']);

        return response()->json($post->slug);
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
    {
        $post = $this->postRepository
            ->getPostWithTrashedAndTags($post->id);

        return new PostResource($post);
    }

    public function update(AdminPostUpdateRequest $request, Post $post)
    {
        $post->update($request->all());

        if($request->hasFile('img')) {
            $this->setImage(
                $request->file('img'),
                '/posts',
                $post->img,
            );
            $post->img = $this->updateImage();
            $post->save();
        }

        $post->tags()->sync($request['tags']);

        return response()->json($post->slug, 200);
    }

    public function destroy($id)
    {
        $post = $this->postRepository
            ->delete(explode(",", $id));

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $post = $this->postRepository
            ->restorePost(explode(",", $id));

        return response()->json(null, 204);

    }

    public function getDeletedPosts()
    {
        $posts = $this->postRepository
            ->getDeletedPosts();

        return PostResource::collection($posts);
    }

    public function forceDelete($id)
    {
        $posts = $this->postRepository
            ->whereInWithTrashed(explode(",", $id));

        foreach ($posts as $post) {
            if(Storage::exists("/posts/".$post->img)){
                Storage::delete("/posts/".$post->img);
            }
        }

        $post = $this->postRepository
            ->forceDelete(explode(",", $id));

        return response()->json(null, 204);

    }

    public function search(Request $request) {
        $query = $request->query('search');
        $posts = $this->postRepository->search($query);

        return PostResource::collection($posts);
    }
}
