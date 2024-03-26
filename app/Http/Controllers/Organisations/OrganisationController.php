<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganisationController extends Controller
{
    public function get_organisations() {
        $organisations = Organisation::all();

        foreach ($organisations as $organisation) {
            $organisation->num_of_employees = $organisation->get_num_of_employees();
        }

        return view('organisations', ['organisations' => $organisations]);
    }

    public function add_organisation(Request $request) {
        $validate_data = $request->validate([
            'organisation' => ['required'],
            'email' => ['required', 'email', Rule::unique('organisations', 'email')],
            'phone_number' => ['required'],
        ]);

        $address = Address::create();

        $validate_data['address_id'] = $address->id;

        $organisation = Organisation::create($validate_data);

        $organisation->num_of_employees = $organisation->get_num_of_employees();

        return response()->json(['message' => 'Organiation created successfully', 'organisation' => $organisation]);
    }

    public function delete_organisation(Request $request) {
        $validate_data = $request->validate([
            'organisation_id' => ['required'],
        ]);

        $organisation = Organisation::find($validate_data['organisation_id']);

        $organisation->delete();

        return response()->json(['message' => 'Organisation deleted successfully']);
    }

    public function modify_organisation(Request $request)
    {
        $validate_data = $request->validate([
            'organisation_id' => ['required'],
            'organisation' => ['required'],
            'email' => ['required', 'email'],
            'phone_number' => ['required'],
        ]);

        $organisation = Organisation::find($validate_data['organisation_id']);

        unset($validate_data['organisation_id']);
        $organisation->update($validate_data);

        return response()->json(['message' => 'Organisation modified successfully']);
    }

    public function get_search_organisations(Request $request) {
        $organisation_id = $request->input('organisation_id', null);
        $email = $request->input('email', null);
        $phone_number = $request->input('phone_number', null);
        $empl_min = $request->input('empl_min', null);
        $empl_max = $request->input('empl_max', null);

        $organisationQuery = Organisation::query();

        if ($organisation_id) {
            $organisationQuery->where(function ($query) use ($organisation_id) {
                $query->where('id', 'like', '%' . $organisation_id . '%');
            });
        }

        if ($email) {
            $organisationQuery->where(function ($query) use ($email) {
                $query->where('id', 'like', '%' . $email . '%');
            });
        }

        if ($phone_number) {
            $organisationQuery->where(function ($query) use ($phone_number) {
                $query->where('id', 'like', '%' . $phone_number . '%');
            });
        }

        if ($empl_min !== null && $empl_max !== null) {
            $organisationQuery->whereBetween('price', [$empl_min, $empl_max]);
        } elseif ($empl_min !== null) {
            $organisationQuery->where('price', '>', $empl_min);
        } elseif ($empl_max !== null) {
            $organisationQuery->where('price', '<', $empl_max);
        }

        $organisations = $organisationQuery->get();

        foreach ($organisations as $organisation) {
            $organisation->num_of_employees = $organisation->get_num_of_employees();
        }

        return response()->json([
            'message' => 'Orders returned successfully',
            'organisations' => $organisations,
        ]);
    }

    public function select_organisations(Request $request) {
        $search_term = $request->search_term;
        $organisations = Organisation::where('organisation', 'like', '%' . $search_term . '%')->get();

        return response()->json(['organisations' => $organisations]);
    }
}
