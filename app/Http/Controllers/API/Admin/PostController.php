<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\Post\PostsResource;
use App\Models\Slider;
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

    public function index(Request $request)
    {
        $posts = $this->postRepository->getAllPosts($request->query('take'));

        return PostsResource::collection($posts);
    }

    public function create(Request $request)
    {
        //
    }

    public function store(AdminPostCreateRequest $request)
    {
        $post = new Post($request->all());
        $post->img = $this->setImage('img','/posts');

        $post->save();
        $this->uploadImage();
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
            $post->img = $this->setImage('img','posts', $post->img);

            $post->save();
            $this->updateImage();
        }

        $post->tags()->sync($request['tags']);

        return response()->json($post->slug, 200);
    }

    public function destroy($id)
    {
       $slider_exist = Slider::whereIn('post_id', explode(",", $id))->count();
       if ($slider_exist) return response()->json('Удаление не возможно! Этот пост используется в слайдере!', 409);

        $this->postRepository
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

        return PostsResource::collection($posts);
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
        $take = $request->query('take');

        $posts = $this->postRepository->search($query, $take);

        return PostsResource::collection($posts);
    }
}
