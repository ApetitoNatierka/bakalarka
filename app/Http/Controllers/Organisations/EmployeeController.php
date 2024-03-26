<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function get_employees() {
        $employees = Employee::all();

        $user = User::find(auth()->id());

        foreach ($employees as $employee) {
            $employee->email = $employee->user->email;
            $employee->phone_number = $employee->user->phone_number;
        }

        return view('employees', ['employees' => $employees, 'user' => $user]);
    }

    public function add_employee(Request $request) {
        $validate_data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'organisation_id' => ['required', 'exists:organisations,id'],
            'surname' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'department' => ['required', 'string'],
            'position' => ['required', 'string'],
            'identification_number' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'start_date' => ['required', 'date'],
        ]);

        $employee =  Employee::create($validate_data);

        $employee->email = $employee->user->email;
        $employee->phone_number = $employee->user->phone_number;

        $user = $employee->user;

        return response()->json(['message' => 'Employee created successfully', 'employee' => $employee, 'user' => $user]);

    }
}
