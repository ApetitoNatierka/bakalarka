@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Organisations</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_organisation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_organisations">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($organisations))
                    <div class="card p-3">
                        <table class="organisations_table" id="organisations_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Organisation number</th>
                                <th>Organsation name</th>
                                <th>Email</th>
                                <th>Phone number</th>
                                <th>Num. of employees</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($organisations))
                                @foreach($organisations as $organisation)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-organisation-id="{{ $organisation->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/organisation/{{$organisation->id}}"
                                                           class="dropdown-item detail_organisation" id="detail_organisation"
                                                           data-organisation-id="{{ $organisation->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_organisation" id="modify_organisation"
                                                           data-organisation-id="{{ $organisation->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_organisation" id="delete_organisation"
                                                           data-organisation-id="{{ $organisation->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="oorganisation_number"
                                                   value="{{ $organisation->id }}" disabled></td>
                                        <td>
                                            <input type="text" class="form-control" name="organisation"
                                                   value="{{ $organisation->organisation }}">
                                        </td>
                                        <td><input type="text" class="form-control" name="email"
                                                   value="{{ $organisation->email }}"></td>
                                        <td><input type="text" class="form-control" name="phone_number"
                                                   value="{{ $organisation->phone_number }}" ></td>
                                        <td><input type="text" class="form-control" name="num_of_employees"
                                                   value="{{ $organisation->num_of_employees }}" disabled></td>
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

    <div id="organisation_dialog" class="dialog" style="display: none;">
        <form id="organisation_form">
            @csrf
            <label>
                <input type="text" name="organisation" id="organisation" placeholder="Organisation name">
            </label><br>
            <label>
                <input type="text" name="email" id="email" placeholder="Email">
            </label><br>
            <label>
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone number">
            </label><br>
            <button type="button" id="new_organisation">New</button>
            <button type="button" id="cancel_organisation">Cancel</button>
        </form>
    </div>

    <script src="{{ asset('js/manage_organisations.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
