<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get_products() {
        $user = User::find(auth()->id());

        $productsQuery = Product::query();
        $productsQuery->where('type', '=', 'product');

        $entity_type = 'product';

        $products = $productsQuery->get();

        return view('products', ['user' => $user, 'products' => $products, 'entity_type' => $entity_type]);
    }

    public function get_animals() {
        $user = User::find(auth()->id());

        $productsQuery = Product::query();
        $productsQuery->where('type', '=', 'animal');

        $products = $productsQuery->get();
        $entity_type = 'animal';

        return view('products', ['user' => $user, 'products' => $products, 'entity_type' => $entity_type]);
    }

    public function get_services() {
        $user = User::find(auth()->id());

        $productsQuery = Product::query();
        $productsQuery->where('type', '=', 'service');

        $products = $productsQuery->get();

        $entity_type = 'service';
        return view('products', ['user' => $user, 'products' => $products, 'entity_type' => $entity_type]);
    }

    public function get_search_products(Request $request) {
        $searchTerm = $request->input('name', '');
        $minPrice = $request->input('min_price', null);
        $maxPrice = $request->input('max_price', null);
        $type = $request->input('type', null);
        $units = $request->input('units', null);

        $productsQuery = Product::query();

        if ($searchTerm) {
            $productsQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($minPrice !== null && $maxPrice !== null) {
            $productsQuery->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice !== null) {
            $productsQuery->where('price', '>', $minPrice);
        } elseif ($maxPrice !== null) {
            $productsQuery->where('price', '<', $maxPrice);
        }

        if ($type !== null) {
            $productsQuery->where(function ($query) use ($type) {
                $query->where('type', 'like', '%' . $type . '%');
            });
        }

        if ($units !== null) {
            $productsQuery->where(function ($query) use ($units) {
                $query->where('units', 'like', '%' . $units . '%');
            });
        }

        $products = $productsQuery->get();

        $user = User::find(auth()->id());

        return response()->json([
            'message' => 'Products returned successfully',
            'products' => $products,
            'user' => $user,
        ]);
    }


    public function add_new_product(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
            'type' => ['required'],
            'units' => ['required']
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
            'type' => ['required'],
            'units' => ['required']
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

    public function select_products(Request $request) {
        $search_term = $request->search_term;
        $products = Product::where('name', 'like', '%' . $search_term . '%')->get();

        return response()->json(['products' => $products]);
    }
}
