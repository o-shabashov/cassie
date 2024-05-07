<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        return ProductsResource::collection(Product::all());
    }

    public function store(ProductsRequest $request)
    {
        $this->authorize('create', Product::class);

        return new ProductsResource(Product::create($request->validated()));
    }

    public function show(Product $products)
    {
        $this->authorize('view', $products);

        return new ProductsResource($products);
    }

    public function update(ProductsRequest $request, Product $products)
    {
        $this->authorize('update', $products);

        $products->update($request->validated());

        return new ProductsResource($products);
    }

    public function destroy(Product $products)
    {
        $this->authorize('delete', $products);

        $products->delete();

        return response()->json();
    }
}
