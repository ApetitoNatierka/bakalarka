<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderLineController extends Controller
{
    public function add_new_order_line(Request $request) {

        $validate_data = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required'],
            'vat_percentage' => ['required'],
        ]);

        $product = Product::find($validate_data['product_id']);

        $order_line_data['order_id'] = $validate_data['order_id'];
        $order_line_data['product_id'] = $product->id;
        $order_line_data['quantity'] = $validate_data['quantity'];
        $order_line_data['unit_price'] = $product->price;
        $order_line_data['vat_percentage'] = $validate_data['vat_percentage'];

        $order_line = OrderLine::create($order_line_data);

        $order = Order::find($order_line->order_id);

        $order_line->order_line = $order->order_lines->count();
        $order_line->product_name = $product->name;
        $order_line->units = $order_line->product->units;
        $order_line->total_order_line_net_amount = $order_line->get_total_net_amount();
        $order_line->total_order_line_gross_amount = $order_line->get_total_gross_amount();
        $order_line->total_net_amount = $order->get_total_net_amount();
        $order_line->total_gross_amount = $order->get_total_gross_amount();

        return response()->json(['message' => 'Order Line created successfully', 'order_line' => $order_line]);

    }

    public function modify_order_line(Request $request) {
        $validate_data = $request->validate([
            'order_line_id' => ['required', 'exists:order_lines,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required'],
            'vat_percentage' => ['required'],
        ]);

        $product = Product::find($validate_data['product_id']);

        $order_line = OrderLine::find($validate_data['order_line_id']);

        $order = $order_line->order;

        $validate_data['unit_price'] = $product->price;

        unset($validate_data['order_line_id']);
        $order_line->update($validate_data);

        $order_line->units = $product->units;
        $order_line->product_name = $product->name;
        $order_line->total_order_line_net_amount = $order_line->get_total_net_amount();
        $order_line->total_order_line_gross_amount = $order_line->get_total_gross_amount();
        $order_line->total_net_amount = $order->get_total_net_amount();
        $order_line->total_gross_amount = $order->get_total_gross_amount();

        return response()->json(['message' => 'Order Line modified successfully', 'order_line' => $order_line]);

    }

    public function delete_order_line(Request $request) {
        $validated_data = $request->validate([
            'order_line_id' => ['required'],
        ]);

        $order_line = OrderLine::find($validated_data['order_line_id']);
        $order = $order_line->order;
        $order_line->delete();

        $order->load('order_lines');

        $order->total_net_amount = $order->get_total_net_amount();
        $order->total_gross_amount = $order->get_total_gross_amount();

        $order_line_index = 1;
        foreach ($order->order_lines as $order_line) {
            $order_line->order_line = $order_line_index;
            $order_line_index++;
        }

        return response()->json(['message' => 'Order line deleted successfully', 'order' => $order]);

    }


}
