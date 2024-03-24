<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\AddressLine;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $order->total_net_amount = $order->get_total_net_amount();
        $order->total_gross_amount = $order->get_total_gross_amount();
        $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

        return response()->json(['message' => 'Order created successfully', 'order' => $order]);
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
        $validate_data = $request->validate([
            'order_id' => ['required'],
            'state' => ['required'],
        ]);

        $order = Order::find($validate_data['order_id']);

        unset($validate_data['order_id']);
        $order->update($validate_data);

        return response()->json(['message' => 'Order modified successfully']);
    }

    public function get_order(Order $order) {
        $order->total_net_amount = $order->get_total_net_amount();
        $order->total_gross_amount = $order->get_total_gross_amount();
        $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

        $order_line_index = 1;
        foreach ($order->order_lines as $order_line) {
            $order_line->product_name = $order_line->product->name;
            $order_line->total_gross_amount = $order_line->get_total_gross_amount();
            $order_line->total_net_amount = $order_line->get_total_net_amount();
            $order_line->units = $order_line->product->units;
            $order_line->order_line = $order_line_index;
            $order_line_index++;
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
        $total_net_amount_min = $request->input('total_net_amount_min', null);
        $total_net_amount_max = $request->input('total_net_amount_max', null);
        $total_gross_amount_min = $request->input('total_gross_amount_min', null);
        $total_gross_amount_max = $request->input('total_gross_amount_max', null);

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
            $order->total_net_amount = $order->get_total_net_amount();
            $order->total_gross_amount = $order->get_total_gross_amount();
            $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

            return $order;
        });

        $filteredOrders = $orders->filter(function ($order) use ($total_net_amount_min, $total_net_amount_max) {
            if ($total_net_amount_min !== null && $total_net_amount_max !== null) {
                return $total_net_amount_min <= $order->total_net_amount && $total_net_amount_max >= $order->total_net_amount;
            } elseif ($total_net_amount_min !== null) {
                return $total_net_amount_max <= $order->total_net_amount;
            } elseif ($total_net_amount_max !== null) {
                return $total_net_amount_max >= $order->total_net_amount;
            }

            return true;
        });

        $filteredOrders = $orders->filter(function ($order) use ($total_gross_amount_min, $total_gross_amount_max) {
            if ($total_gross_amount_min !== null && $total_gross_amount_max !== null) {
                return $total_gross_amount_min <= $order->total_gross_amount && $total_gross_amount_max >= $order->total_gross_amount;
            } elseif ($total_gross_amount_min !== null) {
                return $total_gross_amount_max <= $order->total_gross_amount;
            } elseif ($total_gross_amount_max !== null) {
                return $total_gross_amount_max >= $order->total_gross_amount;
            }

            return true;
        });

        return response()->json([
            'message' => 'Orders returned successfully',
            'orders' => $filteredOrders,
        ]);
    }

    public function download_order(Order $order)
    {
        $order->customer_name = $order->customer ? ($order->customer->company ? $order->customer->company->company : $order->customer->name) : '';

        $company = (bool)$order->customer->company;
        $customer = ($order->customer->company ? $order->customer->company : $order->customer) ;

        $customer_address = Address::find($customer->address_id);
        $customer_address_line = $customer_address->addresses->first();

        $order_lines = $order->order_lines;

        // Načítanie šablóny
        $templatePath = resource_path('tex/order_template.tex');
        $templateContent = file_get_contents($templatePath);

        // Nahradenie placeholderov reálnymi dátami
        $filledTemplate = str_replace('%ORDER_ID%', $order->id, $templateContent);

        //Customer Address
        $filledTemplate = str_replace('%CUSTOMER_NAME%', $company ? $customer->company : $customer->name, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_ADDRESS_COUNTRY%', $customer_address_line->country, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_ADDRESS_REGION%', $customer_address_line->region, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_ADDRESS_CITY%', $customer_address_line->city, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_ADDRESS_STREET%', $customer_address_line->street, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_ADDRESS_HOUSE_NUMBER%', $customer_address_line->house_number, $filledTemplate);
        $filledTemplate = str_replace('%CUSTOMER_POSTAL_CODE%', $customer_address_line->postal_code, $filledTemplate);
        //$filledTemplate = str_replace('%CUSTOMER_ICO%', $customer_address_line->id, $templateContent);

        //created
        $filledTemplate = str_replace('%CREATED_DATE%', $order->get_created(), $filledTemplate);

        $orderLinesLatex = "";
        $line_no = 0;
        /*
        foreach ($order_lines as $order_line) {
            $line_no++;
            $product = $order_line->product;

            $orderLinesLatex .= "\\hline\n";
            $orderLinesLatex .= "\\multicolumn{1}{|c|}{$line_no} & \multicolumn{2}{c|}{$product->id} & \multicolumn{2}{c|}{$product->description} & {$product->name} \\";
            $orderLinesLatex .= "\\hline\n";
            $orderLinesLatex .= "{$order_line->quantity} & {$order_line->units} & {$order_line->unit_price} & {$order_line->vat_percentage} & {$order_line->get_total_net_amount()} & {$order_line->get_total_gross_amount()} \\";
            $orderLinesLatex .= "\\hline\n";
        }
        */
        foreach ($order_lines as $order_line) {
            $line_no++;
            $product = $order_line->product;

            // Prvý riadok pre order_line
            $orderLinesLatex .= "\\hline\n";
            $orderLinesLatex .= "\\multicolumn{1}{|c|}{$line_no} & \\multicolumn{1}{c|}{$product->id} & \\multicolumn{3}{p{4cm}|}{".str_replace('&', '\&', $product->description)."} & {$product->name} \\\\\n";
            $orderLinesLatex .= "\\hline\n";

            // Druhý riadok pre order_line
            $orderLinesLatex .= " {$order_line->quantity} & {$product->units} & {$order_line->unit_price} & {$order_line->vat_percentage} & {{$order_line->get_total_net_amount()}} & {$order_line->get_total_gross_amount()} \\\\[5pt]\n";

            $orderLinesLatex .= "\\hline\n";

        }

        $filledTemplate = str_replace('%orderLinesLatex%', $orderLinesLatex, $filledTemplate);


        //Total order amnt
        $filledTemplate = str_replace('%TOTAL_NET_AMOUNT%', $order->get_total_net_amount(), $filledTemplate);
        $filledTemplate = str_replace('%TOTAL_GROSS_AMOUNT%', $order->get_total_gross_amount(), $filledTemplate);

        // Uloženie dočasného LaTeX súboru
        $texFilePath = 'order_' . time() . '.tex';
        Storage::disk('pdfs')->put($texFilePath, $filledTemplate);

        // Cesta k dočasnému LaTeX súboru
        $texFileFullPath = Storage::disk('pdfs')->path($texFilePath);

        // Spustenie LaTeX kompilátora a generovanie PDF
       // $command = "pdflatex -interaction=nonstopmode -output-directory=" . escapeshellarg(dirname($texFileFullPath)) . " " . escapeshellarg($texFileFullPath);
        //$command .= " 2>&1";
        $command = "C:\\texlive\\2024\\bin\\windows\\pdflatex -interaction=nonstopmode -output-directory=" . escapeshellarg(dirname($texFileFullPath)) . " " . escapeshellarg($texFileFullPath);
        $command .= " 2>&1";

        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            // Kompilácia zlyhala, vypíšte chyby
            return response()->json(['error' => 'LaTeX compilation failed', 'details' => implode("\n", $output)], 500);
        }

        // Odstránenie dočasného .tex súboru
        Storage::disk('pdfs')->delete($texFilePath);

        // Názov generovaného PDF súboru (predpokladáme rovnaký názov ako .tex s výnimkou prípony)
        $pdfFileName = str_replace('.tex', '.pdf', $texFilePath);

        // Vrátenie PDF súboru užívateľovi
        if (Storage::disk('pdfs')->exists($pdfFileName)) {
            return response()->download(Storage::disk('pdfs')->path($pdfFileName), 'order.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        } else {
            return response()->json(['error' => 'Failed to generate PDF.'], 500);
        }
    }
}
