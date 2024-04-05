@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                @if($customer)
                    <h1 class="card-title">Customers</h1>
                @else
                    <h1 class="card-title">Suppliers</h1>
                @endif
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_company">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_companies">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($companies))
                    <div class="card p-3">
                        <table class="company_table" id="company_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Company number</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone number</th>
                                <th>ICO</th>
                                <th>DIC</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($companies))
                                @foreach($companies as $company)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-company-id="{{ $company->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/company/{{$company->id}}"
                                                           class="dropdown-item detail_company" id="detail_company"
                                                           data-company-id="{{ $company->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_company" id="modify_company"
                                                           data-company-id="{{ $company->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_company" id="delete_company"
                                                           data-company-id="{{ $company->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="company_number"
                                                   value="{{ $company->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="company"
                                                   value="{{ $company->company }}" ></td>
                                        <td><input type="text" class="form-control" name="email"
                                                   value="{{ $company->email }}" ></td>
                                        <td><input type="text" class="form-control" name="phone_number"
                                                   value="{{ $company->phone_number }}" ></td>
                                        <td><input type="text" class="form-control" name="ico"
                                                   value="{{ $company->ICO }}" ></td>
                                        <td><input type="text" class="form-control" name="dic"
                                                   value="{{ $company->DIC }}" ></td>
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

    <div id="company_dialog" class="dialog" style="display: none;">
        <form id="company_form">
            @csrf
            <label>
                <input type="text" name="company" id="new_company_name" placeholder="Company">
            </label><br>
            <label>
                <input type="text" name="email" id="new_email" placeholder="Email">
            </label><br>
            <label>
                <input type="text" name="phone_number" id="new_phone_number" placeholder="Phone number">
            </label><br>
            <label>
                <input type="text" name="ico" id="new_ico" placeholder="ICO">
            </label><br>
            <label>
                <input type="text" name="dic" id="new_dic" placeholder="DIC">
            </label><br>
            <button type="button" id="new_company">New</button>
            <button type="button" id="cancel_company">Cancel</button>

        </form>
    </div>

    @if($customer)
        <input id="new_company_type" type="hidden" value='Customer'>
    @else
        <input id="new_company_type" type="hidden" value='Supplier'>
    @endif

    <script src="{{ asset('js/manage_companies.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
