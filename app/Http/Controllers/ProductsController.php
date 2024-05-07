<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Products;

class ProductsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Products::class);

        return ProductsResource::collection(Products::all());
    }

    public function store(ProductsRequest $request)
    {
        $this->authorize('create', Products::class);

        return new ProductsResource(Products::create($request->validated()));
    }

    public function show(Products $products)
    {
        $this->authorize('view', $products);

        return new ProductsResource($products);
    }

    public function update(ProductsRequest $request, Products $products)
    {
        $this->authorize('update', $products);

        $products->update($request->validated());

        return new ProductsResource($products);
    }

    public function destroy(Products $products)
    {
        $this->authorize('delete', $products);

        $products->delete();

        return response()->json();
    }
}
