<?php

namespace App\Http\Controllers\UserComp;

use App\Http\Controllers\Controller;
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
            'ICO' => ['required'],
            'DIC'=> ['required'],
        ]);

        $address_values['name'] = '';

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

    public function modify_company_info(Request $request) {
        $validatedData = $request->validate([
            'company' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email'],
            'phone_number' => ['required'],
            'type' => ['required'],
            'company_id' => ['required'],
            'ICO' => ['required'],
            'DIC'=> ['required'],
        ]);

        $company = Company::find($validatedData['company_id']);

        unset($validatedData['company_id']);
        $company->update($validatedData);

        return response()->json(['message' => 'Company data saved successfully']);
    }

    public function get_customers() {
        $companiesQuery = Company::query();

        $companiesQuery->where('type', 'like', 'Customer');

        $companies = $companiesQuery->get();

        $customer = true;

        return view('companies', ['companies' => $companies, 'customer' => $customer]);
    }

    public function get_suppliers() {
        $companiesQuery = Company::query();

        $companiesQuery->where('type', 'like', 'Supplier');

        $companies = $companiesQuery->get();

        $customer = false;

        return view('companies', ['companies' => $companies, 'customer' => $customer]);
    }

    public function add_company(Request $request) {
        $validatedData = $request->validate([
            'company' => ['required', 'string', Rule::unique('companies', 'company')],
            'type' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'email' => ['required', 'string'],
            'ICO' => ['required'],
            'DIC'=> ['required'],
        ]);

        $address_values['name'] = '';

        $address = Address::create($address_values);

        $validatedData['address_id'] = $address->id;

        $company = Company::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Company information added successfully.',
            'company'=> $company,
        ]);
    }

    public function delete_company(Request $request) {
        $validate_data = $request->validate([
            'company_id' => ['required'],
        ]);

        $company = Company::find($validate_data['company_id']);

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }

    public function modify_company(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => ['required'],
            'company' => ['required'],
            'type' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'email' => ['required', 'string'],
            'ICO' => ['required'],
            'DIC'=> ['required'],
        ]);

        $company = Company::find($validatedData['company_id']);

        unset($validatedData['company_id']);
        $company->update($validatedData);

        return response()->json(['message' => 'Company modified successfully']);
    }

    public function get_search_companies(Request $request) {
        $company_id = $request->input('company_id', null);
        $company = $request->input('company', null);
        $type = $request->input('type', null);
        $ICO = $request->input('ICO', null);
        $DIC = $request->input('DIC', null);
        $email = $request->input('email', null);
        $phone_number = $request->input('phone_number', null);

        $companyQuery = Company::query();

        if ($company_id) {
            $companyQuery->where(function ($query) use ($company_id) {
                $query->where('id', 'like', '%' . $company_id . '%');
            });
        }

        if ($company) {
            $companyQuery->where(function ($query) use ($company) {
                $query->where('company', 'like', '%' . $company . '%');
            });
        }

        if ($type) {
            $companyQuery->where(function ($query) use ($type) {
                $query->where('type', 'like', '%' . $type . '%');
            });
        }

        if ($ICO) {
            $companyQuery->where(function ($query) use ($ICO) {
                $query->where('ICO', 'like', '%' . $ICO . '%');
            });
        }

        if ($DIC) {
            $companyQuery->where(function ($query) use ($DIC) {
                $query->where('DIC', 'like', '%' . $DIC . '%');
            });
        }

        if ($email) {
            $companyQuery->whereHas('user', function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });
        }

        if ($phone_number) {
            $companyQuery->whereHas('user', function ($query) use ($phone_number) {
                $query->where('phone_number', 'like', '%' . $phone_number . '%');
            });
        }


        $companies = $companyQuery->get();

        return response()->json([
            'message' => 'Companies returned successfully',
            'companies' => $companies,
        ]);
    }
}
