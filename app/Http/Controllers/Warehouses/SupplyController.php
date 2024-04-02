<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supply;
use App\Models\SupplyNumber;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function get_supplies()
    {
        $supplies = Supply::all();
        foreach ($supplies as $supply) {
            $supply->supply_no = $supply->supply_number->supply_number;
        }

        $supply_nos = SupplyNumber::all();

        return view('supplies', ['supplies' => $supplies, 'supply_nos' => $supply_nos]);
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
        ]);

        $supply_no = SupplyNumber::find($validate_data['supply_number_id']);

        $supply =  Supply::create($validate_data);

        $item = Item::where('item_no', '=', $supply_no->supply_number)->where('item_type', '=', 'supply')->first();

        if(!is_null($item)) {
            $item->update([
                'quantity' => $item->quantity + $supply->quantity,
            ]);
        } else {
            $item_data['item_no'] = $supply_no->supply_number;
            $item_data['item_type'] = 'supply';
            $item_data['quantity'] = $supply->quantity;

            $newitem = Item::create($item_data);
        }

        $supply_nos = SupplyNumber::all();

        return response()->json(['message' => 'Supply added successfully', 'supply' => $supply, 'supply_nos' => $supply_nos]);
    }

    public function delete_supply(Request $request) {
        $validate_data = $request->validate([
            'supply_id' => ['required'],
        ]);

        $supply = Supply::find($validate_data['supply_id']);

        $supply->delete();

        return response()->json(['message' => 'Supply deleted successfully']);
    }

    public function modify_supply(Request $request)
    {
        $validate_data = $request->validate([
            'supply_id' => ['required'],
            // Prípadné ďalšie validácie pre dodávky
        ]);

        $supply = Supply::find($validate_data['supply_id']);

        unset($validate_data['supply_id']);

        $supply->update($validate_data);

        return response()->json(['message' => 'Supply modified successfully']);
    }

    public function get_search_supplies(Request $request) {
        // Implementácia vyhľadávania pre dodávky
        // Podobný prístup ako u zvierat, no s atribútmi relevantnými pre dodávky

        $supplies = $supplyQuery->get();

        $supply_nos = SupplyNumber::all();

        return response()->json([
            'message' => 'Supplies returned successfully',
            'supplies' => $supplies,
            'supply_nos' => $supply_nos,
        ]);
    }
}
