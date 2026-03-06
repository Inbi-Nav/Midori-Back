<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request) {
        $data = $request->validated();

        if ($request->file('image')) {

            $file = $request->file('image');

            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);

            $data['image_url'] = 'images/products/' . $filename;
        }

        $product = Product::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }
    
    public function update(UpdateProductRequest $request, Product $product) {
        $user = $request->user();

        if ($product->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $data = $request->validated();

        if ($request->file('image')) {

            $file = $request->file('image');

            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);

            $data['image_url'] = 'images/products/' . $filename;
        }

        $product->update($data);

        return new ProductResource($product);
    }

    public function destroy(Product $product, Request $request)
    {
        $user = $request->user();

        if ($product->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}