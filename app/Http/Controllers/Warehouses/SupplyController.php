<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supply;
use App\Models\SupplyNumber;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class SupplyController extends Controller
{
    public function get_supplies()
    {
        $supplies = Supply::all();
        if (!isNull($supplies)) {
            foreach ($supplies as $supply) {
                $supply->supply_no = $supply->supply_number->supply_number;
            }
        }

        $warehouses = Warehouse::all();
        $supply_nos = SupplyNumber::all();

        return view('supplies', ['supplies' => $supplies, 'supply_nos' => $supply_nos, 'warehouses' => $warehouses]);
    }

    public function add_supply(Request $request) {
        $validate_data = $request->validate([
            'supply_number_id' => ['required', 'exists:supply_numbers,id'],
            'weight' => ['required'],
            'height' => ['required'],
            'description' => ['required'],
            'units' => ['required'],
            'status' => ['required'],
            'quantity' => ['required'],
            'warehouse_id' => ['required'],
        ]);

        $supply_no = SupplyNumber::find($validate_data['supply_number_id']);

        $supply =  Supply::create($validate_data);

        $item = Item::where('item_no', '=', $supply_no->supply_number)->where('item_type', '=', 'supply')->where('warehouse_id', '=', $validate_data['warehouse_id'])->first();

        if(!is_null($item)) {
            $item->update([
                'quantity' => $item->quantity + $supply->quantity,
            ]);
        } else {
            $item_data['item_no'] = $supply_no->supply_number;
            $item_data['item_type'] = 'supply';
            $item_data['quantity'] = $supply->quantity;
            $item_data['warehouse_id'] = $validate_data['warehouse_id'];

            $newitem = Item::create($item_data);
        }

        $supply_nos = SupplyNumber::all();
        $warehouses = Warehouse::all();

        return response()->json(['message' => 'Supply added successfully', 'supply' => $supply, 'supply_nos' => $supply_nos, 'warehouses' => $warehouses]);
    }

    public function delete_supply(Request $request) {
        $validate_data = $request->validate([
            'supply_id' => ['required'],
        ]);

        $supply = Supply::find($validate_data['supply_id']);

        $supply_no  = $supply->supply_number;

        $item = Item::where('item_no', '=', $supply_no->supply_number)->where('item_type', '=', 'supply')->where('warehouse_id', '=', $supply->warehouse_id)->first();

        $item->quantity = $item->quantity - $supply->quantity;

        if ($item->quantity <= 0) {
            $item->delete();
        }

        $supply->delete();

        return response()->json(['message' => 'Supply deleted successfully']);
    }

    public function modify_supply(Request $request)
    {
        $validate_data = $request->validate([
            'supply_id' => ['required'],
            'supply_number_id' => ['required', 'exists:supply_numbers,id'],
            'weight' => ['required'],
            'height' => ['required'],
            'description' => ['required'],
            'units' => ['required'],
            'status' => ['required'],
            'quantity' => ['required'],
            'warehouse_id' => ['required'],
        ]);

        $supply = Supply::find($validate_data['supply_id']);
        $supply_numbr = SupplyNumber::find($validate_data['supply_number_id']);
        $new_supply_numbr_no = $supply_numbr->supply_number;

        if ($supply->quantity != $validate_data['quantity']) {
            $supply_no = $supply->supply_number;
            $item_temp = Item::where('item_no', '=', $supply_no->supply_number)->where('item_type', '=', 'supply')->where('warehouse_id', '=', $supply->warehouse_id)->first();
            $item_temp->update([
                'quantity' => $item_temp->quantity + ($validate_data['quantity'] - $supply->quantity),
            ]);
        }

        if ($supply->warehouse_id != $validate_data['warehouse_id'] || $supply->supply_number_id != $validate_data['supply_number_id']) {
            $supply_no = $supply->supply_number;

            $item_minus = Item::where('item_no', '=', $supply_no->supply_number)->where('item_type', '=', 'supply')->where('warehouse_id', '=', $supply->warehouse_id)->first();

            $item_plus = Item::where('item_no', '=', $new_supply_numbr_no)->where('item_type', '=', 'supply')->where('warehouse_id', '=', $validate_data['warehouse_id'])->first();

            $item_minus->update([
                'quantity' => $item_minus->quantity - ($supply->quantity + ($validate_data['quantity'] - $supply->quantity)),
            ]);

            if (is_null($item_plus)) {
                $item_data['item_no'] = $new_supply_numbr_no;
                $item_data['item_type'] = 'supply';
                $item_data['quantity'] = $validate_data['quantity'];
                $item_data['warehouse_id'] = $validate_data['warehouse_id'];

                $item_plus = Item::create($item_data);
            } else {
                $item_plus->update([
                    'quantity' => $item_plus->quantity + $validate_data['quantity'],
                ]);
            }

            if ($item_minus->quantity <= 0) {
                $item_minus->delete();
            }
        }

        unset($validate_data['supply_id']);

        $supply->update($validate_data);

        return response()->json(['message' => 'Supply modified successfully']);
    }

    public function get_search_supplies(Request $request) {
        $supply_id = $request->input('supply_id', null);
        $supply_number = $request->input('supply_number_id', null);
        $weight = $request->input('weight', null);
        $height = $request->input('height', null);
        $description = $request->input('description', null);
        $units = $request->input('units', null);
        $status = $request->input('status', null);
        $quantity = $request->input('quantity', null);
        $warehouse = $request->input('warehouse', null);

        $supplyQuery = Supply::query();

        if ($supply_id) {
            $supplyQuery->where(function ($query) use ($supply_id) {
                $query->where('id', 'like', '%' . $supply_id . '%');
            });
        }

        if ($weight) {
            $supplyQuery->where(function ($query) use ($weight) {
                $query->where('weight', 'like', '%' . $weight . '%');
            });
        }

        if ($height) {
            $supplyQuery->where(function ($query) use ($height) {
                $query->where('height', 'like', '%' . $height . '%');
            });
        }


        if ($description) {
            $supplyQuery->where(function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }

        if ($units) {
            $supplyQuery->where(function ($query) use ($units) {
                $query->where('units', 'like', '%' . $units . '%');
            });
        }

        if ($status) {
            $supplyQuery->where(function ($query) use ($status) {
                $query->where('status', 'like', '%' . $status . '%');
            });
        }

        if ($quantity) {
            $supplyQuery->where(function ($query) use ($quantity) {
                $query->where('quantity', 'like', '%' . $quantity . '%');
            });
        }

        if ($supply_number) {
            $supplyQuery->wherehas('supply_number',function ($query) use ($supply_number) {
                $query->where('supply_number', 'like', '%' . $supply_number . '%');
            });
        }

        if ($warehouse) {
            $supplyQuery->wherehas('warehouse',function ($query) use ($warehouse) {
                $query->where('warehouse', 'like', '%' . $warehouse . '%');
            });
        }

        $supplies = $supplyQuery->get();

        $supply_nos = SupplyNumber::all();
        $warehouses = Warehouse::all();

        return response()->json([
            'message' => 'Supplies returned successfully',
            'supplies' => $supplies,
            'supply_nos' => $supply_nos,
            'warehouses' => $warehouses
        ]);
    }
}
