
@extends('layout.navigator')
@section('content')

    <link href="{{ asset('css/styles_user_profile.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">User Profile</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="modify_user_info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                        <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary custom-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-x" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6.146 5.146a.5.5 0 0 1 .708 0L8 6.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 7l1.147 1.146a.5.5 0 0 1-.708.708L8 7.707 6.854 8.854a.5.5 0 1 1-.708-.708L7.293 7 6.146 5.854a.5.5 0 0 1 0-.708"></path>
                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"></path>
                    </svg>
                </button>
                <hr>
                <div class="card p-3">
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
                                <button type="button" class="btn btn-secondary custom-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-secondary custom-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                                        <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-secondary custom-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-x" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M6.146 5.146a.5.5 0 0 1 .708 0L8 6.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 7l1.147 1.146a.5.5 0 0 1-.708.708L8 7.707 6.854 8.854a.5.5 0 1 1-.708-.708L7.293 7 6.146 5.854a.5.5 0 0 1 0-.708"></path>
                                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"></path>
                                    </svg>
                                </button>
                                <hr>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id nisi ut justo ultrices feugiat. Donec tristique odio sit amet augue tincidunt feugiat.</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Address Lines
                    </button>
                    <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <div class="card p-3">
                            <h5 class="card-title">Address lines</h5>
                            <button type="button" class="btn btn-secondary custom-btn" id="add_address_line">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-secondary custom-btn" id="modify_address_line">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                                    <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-secondary custom-btn" id="delete_address_line">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-x" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6.146 5.146a.5.5 0 0 1 .708 0L8 6.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 7l1.147 1.146a.5.5 0 0 1-.708.708L8 7.707 6.854 8.854a.5.5 0 1 1-.708-.708L7.293 7 6.146 5.854a.5.5 0 0 1 0-.708"></path>
                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"></path>
                                </svg>
                            </button>
                            <hr>
                            <table class="address_line_table">
                            <thead>
                            <tr>
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
                                @foreach($user->address()->addresses() as $address)
                                    <tr>
                                        <td>{{ $address->street }}</td>
                                        <td>{{ $address->house_number }}</td>
                                        <td>{{ $address->city }}</td>
                                        <td>{{ $address->region }}</td>
                                        <td>{{ $address->postal_code }}</td>
                                        <td>{{ $address->country }}</td>
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

    <script src="{{ asset('js/manage_user_profile.js')}}"></script>

@endsection
