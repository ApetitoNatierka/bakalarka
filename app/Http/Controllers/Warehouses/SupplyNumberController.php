<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\SupplyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplyNumberController extends Controller
{
    public function get_supply_numbers() {
        $supply_numbers = SupplyNumber::all();

        return view('supply_numbers', ['supply_numbers' => $supply_numbers]);
    }

    public function add_supply_number(Request $request) {
        $validator = Validator::make($request->all(), [
            'supply_number' => ['required', Rule::unique('supply_numbers', 'supply_number')],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('supply_number')) {
                return response()->json(['message' => 'Supply number already exists', 'errors' => $validator->errors()], 422);
            }
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $supply_number = SupplyNumber::create($validator->validated());

        return response()->json(['message' => 'Supply number created successfully', 'supply_number' => $supply_number]);
    }

    public function delete_supply_number(Request $request) {
        $validate_data = $request->validate([
            'supply_number_id' => ['required'],
        ]);

        $supply_number = SupplyNumber::find($validate_data['supply_number_id']);

        if($supply_number) {
            $supply_number->delete();
            return response()->json(['message' => 'Supply number deleted successfully']);
        } else {
            return response()->json(['message' => 'Supply number not found'], 404);
        }
    }

    public function modify_supply_number(Request $request) {
        $validate_data = $request->validate([
            'supply_number_id' => ['required'],
            'supply_number' => ['required'],
            'description' => ['required'],
        ]);

        $supply_number = SupplyNumber::find($validate_data['supply_number_id']);

        if($supply_number) {
            unset($validate_data['supply_number_id']);
            $supply_number->update($validate_data);
            return response()->json(['message' => 'Supply number modified successfully']);
        } else {
            return response()->json(['message' => 'Supply number not found'], 404);
        }
    }

    public function get_search_supply_numbers(Request $request) {
        $supply_number_id = $request->input('supply_number_id', null);
        $supply_number = $request->input('supply_number', null);
        $description = $request->input('description', null);

        $supply_numberQuery = SupplyNumber::query();

        if ($supply_number_id) {
            $supply_numberQuery->where('id', 'like', '%' . $supply_number_id . '%');
        }

        if ($supply_number) {
            $supply_numberQuery->where('supply_number', 'like', '%' . $supply_number . '%');
        }

        if ($description) {
            $supply_numberQuery->where('description', 'like', '%' . $description . '%');
        }

        $supply_numbers = $supply_numberQuery->get();

        return response()->json([
            'message' => 'Supply numbers returned successfully',
            'supply_numbers' => $supply_numbers,
        ]);
    }

    public function select_supply_nos(Request $request) {
        $search_term = $request->search_term;
        $supply_nos = SupplyNumber::where('supply_number', 'like', '%' . $search_term . '%')->get();

        return response()->json(['supply_nos' => $supply_nos]);
    }

}
