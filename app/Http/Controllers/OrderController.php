<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create_order() {
        $user = User::find(auth()->id());

        $order_data = [
            'created' => now(),
            'state' => 'arrival',
            'customer_id' => $user->id,
        ];
        $order = Order::create($order_data);

        $cart = $user->cart;
        $cart_items = $cart->cart_items;

        foreach ($cart_items as $cart_item) {
            $orderLineData = [
                'order_id' => $order->id,
                'product_id' => $cart_item->product_id,
                'quantity' => $cart_item->quantity,
                'unit_price' => $cart_item->unit_price,
            ];

            $orderLine = OrderLine::create($orderLineData);
        }

        foreach ($cart_items as $cart_item) {
            $cart_item->delete();
        }
    }

    public function add_order(Request $request) {
        $validate_data = $request->validate([
            'customer' => ['required', 'exists:users,id'],
        ]);

        $customer = User::find($validate_data['customer']);

        $order_data = [
            'created' => now(),
            'state' => 'arrival',
            'customer_id' =>$customer->id,
        ];

        $order = Order::create($order_data);

        return response()->json(['message' => 'Order created successfully', 'order' => $order, 'customer' => $customer]);
    }

    public function get_orders() {
        $orders = Order::all();

        return view('orders', ['orders' => $orders]);
    }

    public function delete_order(Request $request) {
        $validatedData = $request->validate([
            'order_id' => ['required'],
        ]);

        $order = Order::find($validatedData['order_id']);

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

    public function modify_order(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => ['required'],
            'state' => ['required'],
        ]);

        $order = Order::find($validatedData['order_id']);

        unset($validatedData['order_id']);
        $order->update($validatedData);

        return response()->json(['message' => 'Order modified successfully']);
    }

    public function get_order(Order $order) {

        return view('order', ['order', $order]);
    }
}
