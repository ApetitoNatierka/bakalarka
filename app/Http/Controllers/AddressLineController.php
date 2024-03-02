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
            'street' => ['required', 'string'],
            'house_number' => ['required', 'string'],
            'city' => ['required', 'string'],
            'region' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'country' => ['required', 'string'],
        ]);

        $user = User::find(auth()->id());

        $validatedData['address_id'] = $user->get_address()->get_id();

        AddressLine::create($validatedData);

        return view('user_profile', ['user' => $user]);

    }
}
