@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Warehouses</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_warehouse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_warehouse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($warehouses))
                    <div class="card p-3">
                        <table class="warehouse_table" id="warehouse_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Warehouse No</th>
                                <th>Warehouse</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Manager</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($warehouses))
                                @foreach($warehouses as $warehouse)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-warehouse-id="{{ $warehouse->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/warehouse/{{$warehouse->id}}"
                                                           class="dropdown-item detail_warehouse" id="detail_warehouse"
                                                           data-warehouse-id="{{ $warehouse->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_warehouse" id="modify_warehouse"
                                                           data-warehouse-id="{{ $warehouse->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_warehouse" id="delete_warehouse"
                                                           data-warehouse-id="{{ $warehouse->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="warehouse_number"
                                                   value="{{ $warehouse->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="warehouse"
                                                   value="{{ $warehouse->warehouse }}"></td>
                                        <td><input type="text" class="form-control" name="location"
                                                   value="{{ $warehouse->location }}"></td>
                                        <td><input type="number" class="form-control" name="capacity"
                                                   value="{{ $warehouse->capacity }}"></td>
                                        <td><input type="number" class="form-control" name="manager"
                                                   value="{{ $warehouse->manager_id }}"></td>
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

    <div id="warehouse_dialog" class="dialog" style="display: none;">
        <form id="warehouse_form">
            @csrf
            <label>
                <input type="text" name="warehouse" id="new_warehouse_name" placeholder="Warehouse">
            </label><br>
            <label>
                <input type="text" name="location" id="new_location" placeholder="Location">
            </label><br>
            <label>
                <input type="number" name="capacity" id="new_capacity" placeholder="Capacity">
            </label><br>
            <label>
                <select id="user_select" name="user"></select>
            </label><br>
            <button type="button" id="new_warehouse">New</button>
            <button type="button" id="cancel_warehouse">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_warehouses.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
