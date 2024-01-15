<?php

namespace App\Http\Controllers;

use App\Jobs\ProductLiked;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function like($id)
    {
        try {
            $response = \Http::timeout(60)->get('http://host.docker.internal:8000/api/user');
            $user = $response->json();

            try {
                $product = ProductUser::create([
                    'user_id' => $user['id'],
                    'product_id' => $id,
                ]);

                ProductLiked::dispatch($product->toArray())->onQueue('admin_queue');

                return response()->json([
                    'success' => true,
                    'message' => 'Success like!'
                ], 200);
            }catch (\Illuminate\Http\Client\ConnectionException $e){
                return response()->json([
                    'success' => false,
                    'message' => 'You already liked this product.'
                ], 301);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['error' => 'Connection timeout'], 500);
        }
    }
}
