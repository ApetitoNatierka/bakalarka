<?php

namespace App\Http\Controllers;

use App\Models\AddressLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get_products() {
        $user = User::find(auth()->id());
        $products= Product::all();

        return view('products', ['user' => $user, 'products' => $products]);
    }

    public function get_search_products(Request $request) {
        $searchTerm = $request->input('name', '');
        $minPrice = $request->input('min_price', null);
        $maxPrice = $request->input('max_price', null);

        $productsQuery = Product::query();

        if ($searchTerm) {
            $productsQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like',  '%' . $searchTerm . '%' );
            });
        }

        if ($minPrice !== null && $maxPrice !== null) {
            $productsQuery->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice !== null) {
            $productsQuery->where('price', '>', $minPrice);
        } elseif ($maxPrice !== null) {
            $productsQuery->where('price', '<', $maxPrice);
        }

        $products = $productsQuery->get();

        $user = User::find(auth()->id());

        return response()->json([
            'message' => 'Preducts returned successfully',
            'products' => $products,
            'user' => $user,
        ]);
    }

    public function add_new_product(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);

        $new_product = Product::create($validatedData);

        return response()->json([
            'message' => 'Preduct created successfully',
            'new_product' => $new_product,
        ]);
    }

    public function modify_product(Request $request) {
        $validatedData = $request->validate([
            'product_id' => ['required'],
            'name' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);

        $product = Product::find($validatedData['product_id']);

        unset($validatedData['product_id']);
        $product->update($validatedData);

        return response()->json(['message' => 'Product modified successfully']);

    }

    public function delete_product(Request $request) {
        $validatedData = $request->validate([
            'product_id' => ['required'],
        ]);

        $product = Product::find($validatedData['product_id']);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
