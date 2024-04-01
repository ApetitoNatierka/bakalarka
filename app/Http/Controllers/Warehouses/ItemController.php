<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Item;
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

        $item = Item::find($validate_data['animal_id']);

        if ($item->item_type == 'animal') {

        } else {
            null;
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


        $item->update($validate_data);

        return response()->json(['message' => 'item modified successfully']);
    }
}
