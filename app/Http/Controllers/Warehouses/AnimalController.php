<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalNumber;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;
use function Symfony\Component\String\b;

class AnimalController extends Controller
{
    public function get_animals()
    {
        $animals = Animal::all();
        if (!isNull($animals)) {
            foreach ($animals as $animal) {
                $animal->animal_no = $animal->animal_number->animal_number;
            }
        }

        $animal_nos = AnimalNumber::all();

        return view('animals', ['animals' => $animals, 'animal_nos' => $animal_nos]);
    }

    public function add_animal(Request $request) {
        $validate_data = $request->validate([
            'animal_number_id' => ['required', 'exists:animal_numbers,id'],
            'weight' => ['required'],
            'height' => ['required'],
            'born' => ['required'],
            'condition' => ['required'],
            'gender' => ['required'],
        ]);

        $animal_no = AnimalNumber::find($validate_data['animal_number_id']);

        $animal =  Animal::create($validate_data);

        $item = Item::where('item_no', '=', $animal_no->animal_number)->where('item_type', '=', 'animal')->first();

        if(!is_null($item)) {
            $item->update([
                'quantity' => $item->quantity + 1,
            ]);
        } else {
            $item_data['item_no'] = $animal_no->animal_number;
            $item_data['item_type'] = 'animal';
            $item_data['quantity'] = 1;

            $newitem = Item::create($item_data);
        }

        $animal_nos = AnimalNumber::all();

        return response()->json(['message' => 'Animal deleted successfully', 'animal' => $animal, 'animal_nos' => $animal_nos]);

    }

    public function delete_animal(Request $request) {
        $validate_data = $request->validate([
            'animal_id' => ['required'],
        ]);

        $animal= Animal::find($validate_data['animal_id']);

        $animal->delete();

        return response()->json(['message' => 'Animal deleted successfully']);
    }

    public function modify_animal(Request $request)
    {
        $validate_data = $request->validate([
            'animal_id' => ['required'],
            'animal_number_id' => ['required'],
            'weight' => ['required'],
            'height' => ['required'],
            'born' => ['required'],
            'condition' => ['required'],
            'gender' => ['required'],
        ]);

        $animal = Animal::find($validate_data['animal_id']);

        unset($validate_data['animal_id']);


        $animal->update($validate_data);

        return response()->json(['message' => 'animal modified successfully']);
    }

    public function get_search_animals(Request $request) {
        $animal_id = $request->input('animal_id', null);
        $animal_number = $request->input('animal_number_id', null);
        $weight = $request->input('weight', null);
        $height = $request->input('height', null);
        $born_to = $request->input('born_to', null);
        $born_from = $request->input('born_from', null);
        $condition = $request->input('condition', null);
        $gender = $request->input('gender', null);


        $animalQuery = Animal::query();

        if ($animal_id) {
            $animalQuery->where(function ($query) use ($animal_id) {
                $query->where('id', 'like', '%' . $animal_id . '%');
            });
        }

        if ($condition) {
            $animalQuery->where(function ($query) use ($condition) {
                $query->where('condition', 'like', '%' . $condition . '%');
            });
        }

        if ($weight) {
            $animalQuery->where(function ($query) use ($weight) {
                $query->where('weight', 'like', '%' . $weight . '%');
            });
        }

        if ($height) {
            $animalQuery->where(function ($query) use ($height) {
                $query->where('height', 'like', '%' . $height . '%');
            });
        }

        if ($born_from !== null && $born_to !== null) {
            $animalQuery->whereBetween('born', [$born_from, $born_to]);
        } elseif ($born_from !== null) {
            $animalQuery->where('born', '>=', $born_from);
        } elseif ($born_to !== null) {
            $animalQuery->where('born', '<=', $born_to);
        }

        if ($gender) {
            $animalQuery->where(function ($query) use ($gender) {
                $query->where('gender', 'like', '%' . $gender . '%');
            });
        }

        if ($animal_number) {
            $animalQuery->wherehas('animal_number',function ($query) use ($animal_number) {
                $query->where('animal_number', 'like', '%' . $animal_number . '%');
            });
        }

        $animals = $animalQuery->get();

        $animal_nos = AnimalNumber::all();


        return response()->json([
            'message' => 'animals returned successfully',
            'animals' => $animals,
            'animal_nos' => $animal_nos,
        ]);
    }
}
