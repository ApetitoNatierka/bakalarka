@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_employees.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Employees</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_employee">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_employees">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($employees))
                    <div class="card p-3">
                        <table class="employee_table" id="employee_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Employee No</th>
                                <th>Surname</th>
                                <th>Last Name</th>
                                <th>Position</th>
                                <th>Identification No</th>
                                <th>Department</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($employees))
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-employee-id="{{ $employee->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/employee/{{$employee->id}}"
                                                           class="dropdown-item detail_employee" id="detail_employee"
                                                           data-employee-id="{{ $employee->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_employee" id="modify_employee"
                                                           data-employee-id="{{ $employee->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_employee" id="delete_employee"
                                                           data-employee-id="{{ $employee->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="employee_number"
                                                   value="{{ $employee->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="surname"
                                                   value="{{ $employee->surname }}" ></td>
                                        <td><input type="text" class="form-control" name="last_name"
                                                   value="{{ $employee->last_name }}" ></td>
                                        @if($user->role == 'admin')
                                            <td><input type="text" class="form-control" name="position"
                                                       value="{{ $employee->position }}" ></td>
                                        @else
                                        <td><input type="text" class="form-control" name="position"
                                                   value="{{ $employee->position }}" disabled></td>
                                        @endif
                                        <td><input type="text" class="form-control" name="identification_number"
                                                   value="{{ $employee->identification_number }}" ></td>
                                        @if($user->role == 'admin')
                                            <td><input type="text" class="form-control" name="department"
                                                       value="{{ $employee->department }}" ></td>
                                        @else
                                            <td><input type="text" class="form-control" name="department"
                                                       value="{{ $employee->department }}" disabled></td>
                                        @endif
                                        <td><input type="text" class="form-control" name="email"
                                                   value="{{ $employee->email }}" ></td>
                                        <td><input type="text" class="form-control" name="phone_number"
                                                   value="{{ $employee->phone_number }}" ></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card p-3">
                        @csrf
                        <div class="row mb-3 align-content-center">
                            <p> no data found</p>
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="employee_dialog" class="dialog" style="display: none;">
        <form id="employee_form">
            @csrf
            <label>
                <select id="user_select" name="user"></select>
            </label><br>
            <label>
                <select id="organisation_select" name="organisation"></select>
            </label><br>
            <label>
                <input type="text" name="surname" id="surname" placeholder="surname">
            </label><br>
            <label>
                <input type="text" name="last_name" id="last_name" placeholder="last name">
            </label><br>
            <label>
                <input type="text" name="position" id="position" placeholder="position">
            </label><br>
            <label>
                <input type="text" name="identification_number" id="identification_number" placeholder="identification number">
            </label><br>
            <label>
                <input type="text" name="department" id="department" placeholder="department">
            </label><br>
            <label>
                <input type="date" name="birth_date" id="birth_date" placeholder="birth date">
            </label><br><label>
                <input type="date" name="start_date" id="start_date" placeholder="start date">
            </label><br>

            <button type="button" id="new_employee">New</button>
            <button type="button" id="cancel_employee">Cancel</button>
        </form>
    </div>

    <script src="{{ asset('js/manage_employees.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
