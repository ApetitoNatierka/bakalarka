<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Item;
use App\Models\Supply;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function get_items()
    {
        $items = Item::all();

        $warehouses = Warehouse::all();

        return view('items', ['items' => $items, 'warehouses' => $warehouses]);
    }

    public function delete_item(Request $request) {
        $validate_data = $request->validate([
            'item_id' => ['required'],
        ]);

        $item = Item::find($validate_data['item_id']);

        $item_no = $item->item_no;
        $item_warehouse_id = $item->warehouse_id;

        if ($item->item_type == 'animal') {
            $animals = Animal::whereHas('animal_number', function ($query) use ($item_no, $item_warehouse_id) {
                $query->where('animal_number', 'like', '%' . $item_no . '%')->where('warehouse_id', '=', $item_warehouse_id);
            })->get();

            foreach ($animals as $animal) {
                $animal->delete();
            }
        } else {
            $supplies = Supply::whereHas('supply_number', function ($query) use ($item_no, $item_warehouse_id) {
                $query->where('supply_number', 'like', '%' . $item_no . '%')->where('warehouse_id', '=', $item_warehouse_id);
            })->get();

            foreach ($supplies as $supply) {
                $supply->delete();
            }
        }

        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function modify_item(Request $request)
    {
        $validate_data = $request->validate([
            'item_id' => ['required'],
            'warehouse_id' => ['required'],
        ]);

        $item = Item::find($validate_data['item_id']);

        unset($validate_data['item_id']);

        $item_no = $item->item_no;
        $item_warehouse_id = $item->warehouse_id;

        if ($item->item_type == 'animal') {
            $animals = Animal::whereHas('animal_number', function ($query) use ($item_no, $item_warehouse_id) {
                $query->where('animal_number', 'like', '%' . $item_no . '%')->where('warehouse_id', '=', $item_warehouse_id);
            })->get();

            foreach ($animals as $animal) {
                $animal->update([
                    'warehouse_id' => $validate_data['warehouse_id'],
                ]);
            }
        } else {
            $supplies = Supply::whereHas('supply_number', function ($query) use ($item_no, $item_warehouse_id) {
                $query->where('supply_number', 'like', '%' . $item_no . '%')->where('warehouse_id', '=', $item_warehouse_id);
            })->get();

            foreach ($supplies as $supply) {
                $supply->update([
                    'warehouse_id' => $validate_data['warehouse_id'],
                ]);
            }
        }

        $items = Item::where('item_no', '=', $item->item_no)->where('item_type', '=', $item->item_type)->where('warehouse_id', '=', $validate_data['warehouse_id'])->where('id', '<>', $item->id);
        if (!is_null($items)) {
            $quantity = 0;
            foreach ($items as $itemN) {
                $quantity = $quantity + $itemN->quantity;
                $itemN->delete();
            }

            $validate_data['quantity'] = $quantity;
        }

        $item->update($validate_data);

        return response()->json(['message' => 'item modified successfully', 'item' => $item]);
    }

    public function get_search_items(Request $request) {
        $item_id = $request->input('item_id', null);
        $item_no = $request->input('item_no', null);
        $item_type = $request->input('item_type', null);
        $quantity = $request->input('quantity', null);
        $warehouse = $request->input('warehouse', null);

        $itemQuery = Item::query();

        if ($item_id) {
            $itemQuery->where(function ($query) use ($item_id) {
                $query->where('id', 'like', '%' . $item_id . '%');
            });
        }

        if ($item_no) {
            $itemQuery->where(function ($query) use ($item_no) {
                $query->where('item_no', 'like', '%' . $item_no . '%');
            });
        }

        if ($item_type) {
            $itemQuery->where(function ($query) use ($item_type) {
                $query->where('item_type', 'like', '%' . $item_type . '%');
            });
        }

        if ($quantity) {
            $itemQuery->where(function ($query) use ($quantity) {
                $query->where('quantity', 'like', '%' . $quantity . '%');
            });
        }

        if ($warehouse) {
            $itemQuery->wherehas('warehouse',function ($query) use ($warehouse) {
                $query->where('warehouse', 'like', '%' . $warehouse . '%');
            });
        }

        $items = $itemQuery->get();

        $warehouses = Warehouse::all();


        return response()->json([
            'message' => 'warehouses returned successfully',
            'items' => $items,
            'warehouses' => $warehouses,
        ]);
    }
}
