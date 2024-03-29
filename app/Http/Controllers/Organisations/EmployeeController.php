<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isNull;

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

    public function delete_employee(Request $request) {
        $validate_data = $request->validate([
            'employee_id' => ['required'],
        ]);

        $employee = Employee::find($validate_data['employee_id']);

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function modify_employee(Request $request)
    {
        $validate_data = $request->validate([
            'employee_id' => ['required'],
            'surname' => ['required'],
            'last_name' => ['required'],
            'position' => ['required'],
            'identification_number' => ['required'],
            'department' => ['required'],
            'email' => ['required', 'email'],
            'phone_number' => ['required'],
        ]);

        $employee = Employee::find($validate_data['employee_id']);
        $user = $employee->user;

        if (!isNull($validate_data['email'])) {
            $user->update([
                'email' => $validate_data['email'],
            ]);
        }

        if (!isNull($validate_data['phone_number'])) {
            $user->update([
                'phone_number' => $validate_data['phone_number'],
            ]);
        }

        $user->update([
            'email' => $validate_data['email'],
            'phone_number' => $validate_data['phone_number'],
        ]);
        unset($validate_data['employee_id'], $validate_data['email'], $validate_data['phone_number']);


        $employee->update($validate_data);

        return response()->json(['message' => 'employee modified successfully']);
    }

    public function get_search_employees(Request $request) {
        $employee_id = $request->input('employee_id', null);
        $surname = $request->input('surname', null);
        $last_name = $request->input('last_name', null);
        $position = $request->input('position', null);
        $department = $request->input('department', null);
        $identification_number = $request->input('identification_number', null);
        $email = $request->input('email', null);
        $phone_number = $request->input('phone_number', null);

        $employeeQuery = Employee::query();

        if ($employee_id) {
            $employeeQuery->where(function ($query) use ($employee_id) {
                $query->where('id', 'like', '%' . $employee_id . '%');
            });
        }

        if ($surname) {
            $employeeQuery->where(function ($query) use ($surname) {
                $query->where('surname', 'like', '%' . $surname . '%');
            });
        }

        if ($last_name) {
            $employeeQuery->where(function ($query) use ($last_name) {
                $query->where('last_name', 'like', '%' . $last_name . '%');
            });
        }

        if ($position) {
            $employeeQuery->where(function ($query) use ($position) {
                $query->where('position', 'like', '%' . $position . '%');
            });
        }

        if ($department) {
            $employeeQuery->where(function ($query) use ($department) {
                $query->where('department', 'like', '%' . $department . '%');
            });
        }

        if ($identification_number) {
            $employeeQuery->where(function ($query) use ($identification_number) {
                $query->where('identificatoin_number', 'like', '%' . $identification_number . '%');
            });
        }

        if ($email) {
            $employeeQuery->whereHas('user', function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });
        }

        if ($phone_number) {
            $employeeQuery->whereHas('user', function ($query) use ($phone_number) {
                $query->where('phone_number', 'like', '%' . $phone_number . '%');
            });
        }


        $employees = $employeeQuery->get();

        foreach ($employees as $employee) {
            $employee->email = $employee->user->email;
            $employee->phone_number = $employee->user->phone_number;
        }

        $user = User::find(auth()->id());

        return response()->json([
            'message' => 'Employees returned successfully',
            'employees' => $employees,
            'user' => $user,
        ]);
    }
}
