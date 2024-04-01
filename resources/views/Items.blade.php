@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_items.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Items</h1>
                <button type="button" class="btn btn-secondary" id="search_items">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($items))
                    <div class="card p-3">
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
                            @if(isset($items))
                                @foreach($items as $item)
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
                                                   value="{{ $item->item_tyoe }}" disabled></td>
                                        <td><input type="number" class="form-control" name="quantity"
                                                   value="{{ $item->quantity }}" disabled></td>
                                        <td>
                                            <select class="form-control" name="warehouse_id">
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" {{ $warehouse->id == $item->warehouseid ? 'selected' : '' }}>{{ $warehouse->id . ' : ' .  $warehouse->warehouse }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeachš
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


    <script src="{{ asset('js/manage_items.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
