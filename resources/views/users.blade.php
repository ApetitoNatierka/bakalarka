@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Users</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_users">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($users))
                    <div class="card p-3">
                        <table class="user_table" id="user_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>User No</th>
                                <th>Name</th>
                                <th>Company Position</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($users))
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-user-id="{{ $user->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><p class="dropdown-item modify_user" id="modify_user"
                                                           data-user-id="{{ $user->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_user" id="delete_user"
                                                           data-user-id="{{ $user->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="user_number"
                                                   value="{{ $user->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="name"
                                                   value="{{ $user->name }}" ></td>
                                        <td><input type="text" class="form-control" name="company_position"
                                                  value="{{ $user->company_position }}"></td>
                                        <td><input type="text" class="form-control" name="email"
                                                   value="{{ $user->email }}" ></td>
                                        <td><input type="text" class="form-control" name="phone_number"
                                                   value="{{ $user->phone_number }}" ></td>
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

    <div id="user_dialog" class="dialog" style="display: none;">
        <form id="user_form">
            @csrf
            <label>
                <input type="text" name="name" id="name" placeholder="Name">
            </label><br>
            <label>
                <input type="email" name="email" id="email" placeholder="Email">
            </label><br>
            <label>
                <input type="text" name="password" id="password" placeholder="Password">
            </label><br>
            <label>
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone number">
            </label><br>
            <label>
                <input type="text" name="company_position" id="company_position" placeholder="Company position">
            </label><br>
            <button type="button" id="new_user">New</button>
            <button type="button" id="cancel_user">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_users.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
