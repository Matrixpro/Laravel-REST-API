<?php

namespace App\Http\Controllers\API;

use App\Contracts\FetchProductsForUserInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductController extends Controller
{
    public function __construct(Product $product)
    {
        $this->builder = $product->query();
    }

    /**
     * Get paginated set of products
     * @param Request                       $request The request
     * @param FetchProductsForUserInterface $query Query builder
     * @return JsonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, FetchProductsForUserInterface $query): JsonResource
    {
        $this->authorize("read", [Product::class, $request]);

        $products = $query->handle(
            builder: $this->builder,
            user_id: $request->user()->id,
        )->cursorPaginate(
            perPage: 10,
            cursor: request("cursor")
        );

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return ProductResource::collection([$product]);
    }
}
