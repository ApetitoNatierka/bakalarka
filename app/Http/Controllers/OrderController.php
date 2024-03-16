<?php

namespace App\Http\Controllers;

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
}
