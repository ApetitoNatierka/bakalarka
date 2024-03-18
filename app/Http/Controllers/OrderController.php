<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $order = Order::create($order_data);

        $total_amount = $order->get_total_amount();

        return response()->json(['message' => 'Order created successfully', 'order' => $order, 'customer' => $customer, 'total_amount' => $total_amount]);
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
        $order->total_amount = $order->get_total_amount();
        $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

        $orderLineIndex = 1;
        foreach ($order->order_lines as $orderLine) {
            $orderLine->product_name = $orderLine->product->name;
            $orderLine->total_amount = $orderLine->get_total_amount();
            $orderLine->units = $orderLine->product->units;
            $orderLine->order_line = $orderLineIndex;
            $orderLineIndex++;
        }

        return view('order', ['order' => $order]);
    }

    public function get_order_empty(Order $order) {

        return view('order');
    }

    public function get_search_orders(Request $request) {
        $order_id = $request->input('order_id', null);
        $state = $request->input('state', null);
        $customer_name = $request->input('customer_name', null);
        $created_from = $request->input('created_from', null);
        $created_to = $request->input('created_to', null);
        $total_amount_min = $request->input('total_amount_min', null);
        $total_amount_max = $request->input('total_amount_max', null);

        $orderQuery = Order::query();

        if ($order_id) {
            $orderQuery->where(function ($query) use ($order_id) {
                $query->where('id', 'like', '%' . $order_id . '%');
            });
        }

        if ($state !== null) {
            $orderQuery->where(function ($query) use ($state) {
                $query->where('state', 'like', '%' . $state . '%');
            });
        }

        if ($customer_name !== null) {
            $orderQuery->whereHas('customer', function ($query) use ($customer_name) {
                $query->where('name', 'like', '%' . $customer_name . '%')
                    ->orWhereHas('company', function ($query) use ($customer_name) {
                        $query->where('company', 'like', '%' . $customer_name . '%');
                    });
            });
        }

        if ($created_from !== null && $created_to !== null) {
            $orderQuery->whereBetween('created', [$created_from, $created_to]);
        } elseif ($created_from !== null) {
            $orderQuery->where('created', '>=', $created_from);
        } elseif ($created_to !== null) {
            $orderQuery->where('created', '<=', $created_to);
        }

        $orders = $orderQuery->get()->map(function ($order) {
            $order->total_amount = $order->get_total_amount();
            $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

            return $order;
        });

        $filteredOrders = $orders->filter(function ($order) use ($total_amount_min, $total_amount_max) {
            $totalAmount = $order->get_total_amount();

            if ($total_amount_min !== null && $total_amount_max !== null) {
                return $total_amount_min <= $totalAmount && $total_amount_max >= $totalAmount;
            } elseif ($total_amount_min !== null) {
                return $total_amount_min <= $totalAmount;
            } elseif ($total_amount_max !== null) {
                return $total_amount_max >= $totalAmount;
            }

            return true;
        });


        return response()->json([
            'message' => 'Orders returned successfully',
            'orders' => $filteredOrders,
        ]);
    }
}
