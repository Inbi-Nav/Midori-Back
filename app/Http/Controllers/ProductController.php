<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $product = Product::all();
        return response()->json($product);
    }

    public function show($id) {

        $product = Product::find($id);

        if($product) {
            return response()-> json($product);
        } else {
            return response() ->json (['message' => 'Producto no encontrado']);
        }
    }

   public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $filename = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('images/products'), $filename);
            $imagePath = '/images/products/' . $filename;
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'material' => $request->material,
            'color' => $request->color,
            'category_id' => $request->category_id,
            'image_url' => $imagePath,
        ]);

        return response()->json($product, 201);
    }


   public function update(Request $request, $id){
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $imagePath = $product->image_url;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $filename = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('images/products'), $filename);

            $imagePath = '/images/products/' . $filename;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'material' => $request->material,
            'color' => $request->color,
            'image_url' => $imagePath,
        ]);

        return response()->json($product);
    }       

    public function destroy ($id) {
        $product = Product::find($id);
        if(!$product) {
            return response()-> json (['message' => 'Producto no encontrado'], 404);
        }

        $product ->delete();
        return response()-> json(['message' => 'Producto eliminado']);
    }
}

