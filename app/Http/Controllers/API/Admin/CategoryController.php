<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoryCreateRequest;
use App\Http\Requests\AdminCheckDuplicateSlug;
use App\Http\Resources\Admin\Category\CategoriesResource;
use App\Models\Category;
use App\Rules\CheckDuplicateSlug;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class CategoryController extends AdminController
{

    public function __construct()
    {
        $this->middleware('can:viewAny,App\Models\Category');
        $this->middleware('can:edit,category')->only(['update', 'updateMenu']);
        $this->middleware('can:create,App\Models\Category')->only(['store']);
        $this->middleware('can:delete,category')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = $request->query('fetch');

        $categories = Category::query()->when(!$query, function ($q, $category) {
            return $q->orderBy('order')->whereNull('parent_id')
                ->with('children');
        })->get();

        return CategoriesResource::collection($categories);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminCategoryCreateRequest $request)
    {
        $category = Category::create($request->all());

        return response()->json('ok');
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminCategoryCreateRequest $request, Category $category)
    {
        $category->update($request->all(['name', 'slug']));

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->children()->delete();
        $category->delete();

        return response()->json('', 204);
    }

    public function updateMenu(AdminCheckDuplicateSlug $request)
    {
        $order = 1;

        foreach($request->all() as $row) {
            $category = Category::find($row['id']);

            $category->order = $order;
            $category->parent_id = $row['parent_id'] ?? null;
            $category->save();

            $order++;
        }

        $categories =  Category::where('parent_id', null)
            ->orderBy('order')
            ->with('children')
            ->get();

        return CategoriesResource::collection($categories);
    }
}
