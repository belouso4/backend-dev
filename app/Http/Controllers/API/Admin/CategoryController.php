<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoryCreateRequest;
use App\Http\Requests\AdminCheckDuplicateSlug;
use App\Http\Resources\Admin\Category\CategoriesResource;
use App\Models\Category;
use App\Repositories\Contracts\ICategory;
use Illuminate\Http\Request;

class CategoryController extends AdminController
{
    protected $category;

    public function __construct()
    {
        $this->middleware('can:viewAny,'.Category::class);
        $this->middleware('can:update,'.Category::class)->only(['update', 'updateMenu']);
        $this->middleware('can:create,'.Category::class)->only(['store']);
        $this->middleware('can:delete,category')->only(['destroy']);

        $this->category = app(ICategory::class);
    }

    public function index(Request $request)
    {
        $query = $request->query('fetch');

        $categories = $this->category->getCategories($query);

        return CategoriesResource::collection($categories);
    }

    public function store(AdminCategoryCreateRequest $request)
    {
        $category = Category::create($request->all());

        return response()->json('ok');
    }

    public function update(AdminCategoryCreateRequest $request, Category $category)
    {
        $category->update($request->all(['name', 'slug']));

        return response()->json();
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->exists()) {
            return response()->json('Категорию с постами запрещено удалять', 409);
        }
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

        $categories =  $this->category->getCategoriesWhereParentIdNull();

        return CategoriesResource::collection($categories);
    }
}
