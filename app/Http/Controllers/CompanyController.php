<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function get_company_profile() {
        $user = User::find(auth()->id());
        $company = $user->company;

        return view('company_profile', ['company' => $company, 'user' => $user]);
    }

    public function add_company_info(Request $request) {
        $validatedData = $request->validate([
            'company' => ['required', 'string', Rule::unique('companies', 'company')],
            'type' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'email' => ['required', 'string'],
        ]);

        $address_values['name'] = $validatedData['company'];

        $address = Address::create($address_values);

        $validatedData['address_id'] = $address->id;

        $company = Company::create($validatedData);

        $user = User::find(auth()->id());

        $user->update(['company_id' => $company->id, 'company_position' => 'admin']);


        return response()->json([
            'success' => true,
            'message' => 'Company information added successfully.',
        ]);
    }
}
