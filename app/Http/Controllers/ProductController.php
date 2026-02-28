<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            ...$request->only(['name','description','price','stock','material','color','category_id']),
            'user_id' => $request->user()->id,
        ]);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $user = $request->user();
        if ($product->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $product->update($request->only([
            'name','description','price','stock','material','color'
        ]));

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $user = request()->user();
        if ($product->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}