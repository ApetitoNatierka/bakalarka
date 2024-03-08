<?php

namespace App\Http\Controllers;

use App\Models\AddressLine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AddressLineController extends Controller
{
    public function add_new_address_line(Request $request) {
        $validatedData = $request->validate([
            'entity_type' => ['required'],
            'street' => ['required', 'string'],
            'house_number' => ['required', 'string'],
            'city' => ['required', 'string'],
            'region' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'country' => ['required', 'string'],
        ]);

        $user = User::find(auth()->id());

        if ($validatedData['entity_type'] = 'user') {
            $address = $user->address;
        } else
        {
            $company = $user->company;
            $address = $company->address;
        }


        if (!$address) {
            return response()->json([
                'message' => 'Address not found for user'
            ], 404);
        }

        $validatedData['address_id'] = $address->id;

        $address_line = AddressLine::create($validatedData);

        return response()->json([
            'message' => 'Address line added successfully',
            'address_line' => $address_line,
        ]);
    }

    public function modify_address_line(Request $request) {
        $validatedData = $request->validate([
            'address_line_id' => ['required'],
            'street' => ['required', 'string'],
            'house_number' => ['required', 'string'],
            'city' => ['required', 'string'],
            'region' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'country' => ['required', 'string'],
        ]);

        $address_line = AddressLine::find($validatedData['address_line_id']);

        unset($validatedData['address_line_id']);
        $address_line->update($validatedData);

        return response()->json(['message' => 'Address line modified successfully']);
    }

    public function delete_address_line(Request $request) {
        $validatedData = $request->validate([
            'address_line_id' => ['required'],
            ]);

        $address_line = AddressLine::find($validatedData['address_line_id']);

        $address_line->delete();

        return response()->json(['message' => 'Address line deleted successfully']);

    }

}
