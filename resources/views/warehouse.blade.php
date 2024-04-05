
@extends('layout.navigator_intra')
@section('content')
    <link href="{{ asset('css/styles_form.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                @if(isset($warehouse))
                    <h1 class="card-title">Warehouse - {{$warehouse->id}}</h1>
               @endif
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_warehouse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                @if(isset($warehouse))
                    <button type="button" class="btn btn-secondary custom-btn" id="modify_warehouse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-secondary" id="delete_warehouse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                        </svg>
                    </button>
                @endif
                <div class="order_lines-container mt-3">
                    <div class="dorpdown mt-3">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Commands
                        </button>
                        <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                            <div class="card p-3">
                                <h5 class="card-title">Commands</h5>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card p-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="col-form-label">Warehouse number</label>
                            @if(isset($warehouse))
                                <input type="number" class="form-control col custom-input" id="warehouse_id" name="warehouse_id" value="{{ $warehouse->id }}" disabled>
                            @else
                                <input type="number" class="form-control col custom-input" id="warehouse_id" name="warehouse_id" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="warehouse" class="col-form-label">Warehouse</label>
                            @if(isset($warehouse))
                                <input type="text" class="form-control col custom-input" id="warehouse" name="warehouse" value="{{ $warehouse->warehouse }}" >
                            @else
                                <input type="text" class="form-control col custom-input" id="warehouse" name="warehouse" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="location" class="col-form-label">Warehouse Location</label>
                            @if(isset($warehouse))
                                <input type="text" class="form-control col custom-input" id="location" name="location" value="{{ $warehouse->location }}" >
                            @else
                                <input type="text" class="form-control col custom-input" id="location" name="location" disabled>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="capacity" class="col-form-label">Capacity</label>
                            @if(isset($warehouse))
                                <input type="number" class="form-control col custom-input" id="capacity" name="capacity" value="{{ $warehouse->capacity }}" >
                            @else
                                <input type="number" class="form-control col custom-input" id="capacity" name="capacity" disabled>
                            @endif
                        </div>
                        <label for="user_select_form">User</label>
                        <select id="user_select_form" name="user">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $warehouse->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(isset($warehouse->items))
                    <div class="item-container mt-3">
                        <div class="dorpdown mt-3">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Item Lines
                            </button>
                            <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                                <div class="card p-3">
                                    <h5 class="card-title">Item Lines</h5>
                                    <hr>
                                    <table class="item_table" id="item_table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Item No Id</th>
                                            <th>Item No</th>
                                            <th>Item Type</th>
                                            <th>Quantity</th>
                                            <th>Warehouse</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($warehouse->items))
                                            @foreach($warehouse->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                                    id="dropdownMenuButton"
                                                                    data-item-id="{{ $item->id }}"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                                                     viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><p class="dropdown-item modify_item" id="modify_item"
                                                                       data-item-id="{{ $item->id }}">Modify</p></li>
                                                                <li><p class="dropdown-item delete_item" id="delete_item"
                                                                       data-item-id="{{ $item->id }}">Delete</p></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="item_id"
                                                               value="{{ $item->id }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="item_no"
                                                               value="{{ $item->item_no }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="item_type"
                                                               value="{{ $item->item_type }}" disabled></td>
                                                    <td><input type="number" class="form-control" name="quantity"
                                                               value="{{ $item->quantity }}" disabled></td>
                                                    <td>
                                                        <select class="form-control" name="warehouse_id">
                                                            @foreach($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}" {{ $warehouse->id == $item->warehouse_id ? 'selected' : '' }}>{{ $warehouse->id . ' : ' .  $warehouse->warehouse }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

    <script src="{{ asset('js/manage_warehouse.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
