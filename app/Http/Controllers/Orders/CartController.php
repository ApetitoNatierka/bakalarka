<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get_cart() {
        $user = User::find(auth()->id());

        $cart = $user?->cart;

        $cart_items = $cart?->cart_items;

        return view('cart', ['cart_items' => $cart_items]);
    }

    public function add_to_cart(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $user = User::find(auth()->id());

        $product = Product::find($validatedData['product_id']);

        $existingCartItem = CartItem::where('cart_id', $user->cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += $validatedData['quantity'];
            $existingCartItem->save();
        } else {
            $cart_item_data['product_id'] = $product->id;
            $cart_item_data['cart_id'] = $user->cart->id;
            $cart_item_data['name'] = $product->get_name();
            $cart_item_data['unit_price'] = $product->get_price();
            $cart_item_data['quantity'] = $validatedData['quantity'];

            $cart_item = CartItem::create($cart_item_data);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
        ]);
    }

    public function delete_cart_item(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => ['required'],
        ]);

        $cart_item = CartItem::find($validatedData['cart_item_id']);

        $cart_item->delete();

        return response()->json(['message' => 'Cart Item deleted successfully']);
    }

    public function modify_cart_item(Request $request) {
        $validatedData = $request->validate([
            'cart_item_id' => ['required'],
            'quantity' => ['required'],
        ]);

        $cart_item = CartItem::find($validatedData['cart_item_id']);

        unset($validatedData['cart_item_id']);
        $cart_item->update($validatedData);

        return response()->json(['message' => 'Cart Item modified successfully', 'unit_price' => $cart_item->unit_price]);

    }
}
