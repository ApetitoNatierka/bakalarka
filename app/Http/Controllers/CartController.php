<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get_cart() {
        $user = User::find(auth()->id());

        $cart = $user->cart;

        $cart_items = $cart->cart_items;

        return view('cart', ['cart_items', $cart_items]);
    }

    public function add_to_cart(Request $request) {
        $validatedData = $request->validate([
            'product_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $user = User::find(auth()->id());

        $product = Product::find($validatedData['product_id']);

        $cart_item_data['product_id'] = $product->id;
        $cart_item_data['cart_id'] = $user->cart->id;
        $cart_item_data['name'] = $product->get_name();
        $cart_item_data['unit_price'] = $product->get_price();
        $cart_item_data['quantity'] = $validatedData['quantity'];

        $cart_item = CartItem::create();
    }
}
