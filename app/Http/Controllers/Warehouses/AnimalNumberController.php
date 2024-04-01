<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\AnimalNumber;
use Illuminate\Http\Request;

class AnimalNumberController extends Controller
{
    public function get_animal_numbers() {
        $animal_numbers = AnimalNumber::all();

        return view('animal_numbers', ['animal_numbers' => $animal_numbers]);
    }

    public function add_animal_number(Request $request) {
        $validate_data = $request->validate([
            'animal_number' => ['required'],
            'description' => ['required'],
        ]);

        $animal_number =  AnimalNumber::create($validate_data);

        return response()->json(['message' => 'Animal number created successfully', 'animal_number' => $animal_number]);

    }

    public function delete_animal_number(Request $request) {
        $validate_data = $request->validate([
            'animal_number_id' => ['required'],
        ]);

        $animal_number = AnimalNumber::find($validate_data['animal_number_id']);

        $animal_number->delete();

        return response()->json(['message' => 'Animal number deleted successfully']);
    }

    public function modify_animal_number(Request $request)
    {
        $validate_data = $request->validate([
            'animal_number_id' => ['required'],
            'animal_number' => ['required'],
            'description' => ['required'],
        ]);

        $animal_number = AnimalNumber::find($validate_data['animal_number_id']);

        unset($validate_data['animal_number_id']);


        $animal_number->update($validate_data);

        return response()->json(['message' => 'animal number modified successfully']);
    }

    public function get_search_animal_numbers(Request $request) {
        $animal_number_id = $request->input('animal_number_id', null);
        $animal_number = $request->input('animal_number', null);
        $description = $request->input('description', null);

        $animal_numberQuery = AnimalNumber::query();

        if ($animal_number_id) {
            $animal_numberQuery->where(function ($query) use ($animal_number_id) {
                $query->where('id', 'like', '%' . $animal_number_id . '%');
            });
        }

        if ($animal_number) {
            $animal_numberQuery->where(function ($query) use ($animal_number) {
                $query->where('animal_number', 'like', '%' . $animal_number . '%');
            });
        }

        if ($description) {
            $animal_numberQuery->where(function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }

        $animal_numbers = $animal_numberQuery->get();

        return response()->json([
            'message' => 'Animal numbers returned successfully',
            'animal_numbers' => $animal_numbers,
        ]);
    }
}
