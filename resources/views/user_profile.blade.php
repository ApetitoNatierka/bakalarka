
@extends('layout.navigator')
@section('content')

    <link href="{{ asset('css/styles_form.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">User Profile</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="modify_user_info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                        <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                    </svg>
                </button>
                <hr>
                <div class="card p-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="col-form-label">Name</label>
                            @if(auth()->id() !== null)
                                <input type="text" class="form-control col custom-input" id="name" name="name" value="{{ $user->name }}">
                            @else
                                <input type="text" class="form-control col custom-input" id="name" name="name">
                            @endif
                        </div>
                        <div class="col">
                            <label for="email" class="col-form-label">Email</label>
                            @if(auth()->id() !== null)
                                <input type="email" class="form-control col custom-input" id="email" name="email" value="{{ $user->email }}">
                            @else
                                <input type="email" class="form-control col custom-input" id="email" name="email">
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="company_position" class="col-form-label">Company Position</label>
                            @if(auth()->id() !== null)
                                <input type="text" class="form-control col custom-input" id="company_position" name="company_position" value="{{ $user->company_position }}" disabled>
                            @else
                                <input type="text" class="form-control col custom-input" id="company_position" name="company_position" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="phone_number" class="col-form-label">Phone Number</label>
                            @if(auth()->id() !== null)
                                <input type="tel" class="form-control col custom-input" id="phone_number" name="phone_number" value="{{ $user->phone_number }}">
                            @else
                                <input type="tel" class="form-control col custom-input" id="phone_number" name="phone_number">
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($user) && $user->get_company_id() !== null)
                    <div class="dropdown mt-3">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Company Information
                        </button>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            <div class="card p-3">
                                <h5 class="card-title">Company Information</h5>
                                <hr>
                                <table class="address_line_table" id="address_lines_table">
                                    <thead>
                                        <tr>
                                            <th>      </th>
                                            <th>Company name</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Phone Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <th>{{$user->company->get_company()}}</th>
                                            <th>{{$user->company->get_email()}}</th>
                                            <th>{{$user->company->get_type()}}</th>
                                            <th>{{$user->company->get_phone_number()}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="addresses-container mt-3">

                <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Address Lines
                    </button>
                    <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                        <div class="card p-3">
                            <h5 class="card-title">Address lines</h5>
                            <button type="button" class="btn btn-secondary custom-btn" id="add_address_line">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                                </svg>
                            </button>
                            <hr>
                            <table class="address_line_table" id="address_lines_table">
                            <thead>
                            <tr>
                                <th>      </th>
                                <th>Street</th>
                                <th>House Number</th>
                                <th>City</th>
                                <th>Region</th>
                                <th>Postal Code</th>
                                <th>Country</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($user))
                                @foreach($user->address->addresses as $address)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-address-line-id="{{ $address->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><p class="dropdown-item modify_address_line" id="modify_address_line" data-address-line-id="{{ $address->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_address_line" id="delete_address_line" data-address-line-id="{{ $address->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="street" value="{{ $address->street }}"></td>
                                        <td><input type="text" class="form-control" name="house_number" value="{{ $address->house_number }}"></td>
                                        <td><input type="text" class="form-control" name="city" value="{{ $address->city }}"></td>
                                        <td><input type="text" class="form-control" name="region" value="{{ $address->region }}"></td>
                                        <td><input type="text" class="form-control" name="postal_code" value="{{ $address->postal_code }}"></td>
                                        <td><input type="text" class="form-control" name="country" value="{{ $address->country }}"></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div id="address_dialog" class="dialog" style="display: none;">
        <form id="address_form">
            @csrf
            <label>
                <input type="text" name="street" id="street" placeholder="Street">
            </label><br>
            <label>
                <input type="text" name="house_number" id="house_number" placeholder="House Number">
            </label><br>
            <label>
                <input type="text" name="city" id="city" placeholder="City">
            </label><br>
            <label>
                <input type="text" name="region" id="region" placeholder="Region">
            </label><br>
            <label>
                <input type="text" name="postal_code" id="postal_code" placeholder="Postal Code">
            </label><br>
            <label>
                <input type="text" name="country" id="country" placeholder="Country">
            </label><br>
            <button type="button" id="new_address">New</button>
            <button type="button" id="cancel_address">Cancel</button>

        </form>
    </div>


    <input id="entity_type" type="hidden" value="user">

    <script src="{{ asset('js/manage_user_profile.js')}}"></script>
    <script src="{{ asset('js/add_address_line.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
