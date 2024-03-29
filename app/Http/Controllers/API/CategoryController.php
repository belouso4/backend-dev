<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $categories = cache('category');

        if ($categories === null) {
            cache(['category' => $categories = Category::whereNull('parent_id')
                ->orderBy('order')
                ->with('children')
                ->get()
            ], now()->addDay());
        }

        return response()->json($categories);
    }
}
