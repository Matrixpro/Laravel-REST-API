<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(Request $request): JsonResource
    {
        $categories = QueryBuilder::for(Category::class)
            ->where('user_id', $request->user()->id)
            ->allowedIncludes(['products'])
            ->allowedFilters('name', AllowedFilter::exact('id'))
            ->jsonPaginate()
            ->appends(request()->query());

        return CategoryResource::collection($categories);
    }
}
