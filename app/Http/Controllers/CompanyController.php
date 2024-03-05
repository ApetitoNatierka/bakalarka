<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function get_company_profile() {
        $user = User::find(auth()->id());
        $company = $user->company;

        return view('company_profile', ['company' => $company]);
    }
}
