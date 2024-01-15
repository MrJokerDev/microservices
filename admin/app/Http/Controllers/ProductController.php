<?php

namespace App\Http\Controllers;

use App\Jobs\ProductCreate;
use App\Jobs\ProductDelete;
use App\Jobs\ProductUpdate;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'products' => Product::all()
            ]);
        }catch (\Exception $exception){
            return $exception;
        }
    }

    public function store(Request $request)
    {
        try {
            $product = Product::create($request->only('title', 'image'));

            ProductCreate::dispatch($product->toArray())->onQueue('main_queue');

            return response()->json([
                'success' => true,
                'message' => 'Product success created.',
                'product' => $product,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => 'Product not created.',
                'product' => $exception,
            ]);
        }
    }

    public function show(string $id)
    {
        try {
            return Product::find($id);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $exception,
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $product = Product::find($id);

            $product->update($request->only('title', 'image'));

            ProductUpdate::dispatch($product->toArray())->onQueue('main_queue');

            return response()->json([
                'success' => true,
                'message' => 'Product success update.',
                'product' => $product,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $exception,
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {
            Product::destroy($id);

            ProductDelete::dispatch($id)->onQueue('main_queue');

            return response()->json([
                'success' => true,
                'message' => 'Product success deleted.'
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $exception
            ]);
        }
    }
}
