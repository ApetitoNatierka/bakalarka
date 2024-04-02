@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_supplies.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Supplies</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_supplies">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_supplies">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($supplies))
                    <div class="card p-3">
                        <table class="supplies_table" id="supplies_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Supply Id</th>
                                <th>Supply No</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Units</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($supplies))
                                @foreach($supplies as $supply)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-supply-id="{{ $supply->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/supply/{{$supply->id}}"
                                                           class="dropdown-item detail_supply" id="detail_supply"
                                                           data-supply-id="{{ $supply->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_supply" id="modify_supply"
                                                           data-supply-id="{{ $supply->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_supply" id="delete_supply"
                                                           data-supply-id="{{ $supply->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="number" class="form-control" name="supply_id"
                                                   value="{{ $supply->id }}" disabled></td>
                                        <td>
                                            <select class="form-control" name="supply_no_id">
                                                @foreach($supply_nos as $supply_no)
                                                    <option value="{{ $supply_no->id }}" {{ $supply_no->id == $supply->item_no_id ? 'selected' : '' }}>{{ $supply_no->id . ' : ' .  $supply_no->item_no }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" name="description"
                                                   value="{{ $supply->description }}"></td>
                                        <td><input type="number" class="form-control" name="quantity"
                                                   value="{{ $supply->quantity }}"></td>
                                        <td><input type="number" class="form-control" name="weight"
                                                   value="{{ $supply->weight }}"></td>
                                        <td><input type="number" class="form-control" name="height"
                                                   value="{{ $supply->height }}"></td>
                                        <td><input type="text" class="form-control" name="units"
                                                   value="{{ $supply->units }}"></td>
                                        <td><input type="text" class="form-control" name="status"
                                                   value="{{ $supply->status }}"></td>
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

    <div id="supply_dialog" class="dialog" style="display: none;">
        <form id="supply_form">
            @csrf
            <label>
                <select id="supply_no_select" name="supply"></select>
            </label><br>
            <label>
                <input type="number" name="weight" id="new_weight" placeholder="weight">
            </label><br>
            <label>
                <input type="number" name="height" id="new_height" placeholder="height">
            </label><br>
            <label>
                <input type="number" name="quantity" id="new_quantity" placeholder="quantity">
            </label><br>
            <label>
                <input type="text" name="units" id="new_units" placeholder="units">
            </label><br>
            <label>
                <input type="text" name="description" id="new_description" placeholder="description">
            </label><br>
            <label>
                <input type="text" name="status" id="new_status" placeholder="status">
            </label><br>
            <button type="button" id="new_supply">New</button>
            <button type="button" id="cancel_supply">Cancel</button>
        </form>
    </div>

    <script src="{{ asset('js/manage_supplies.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
