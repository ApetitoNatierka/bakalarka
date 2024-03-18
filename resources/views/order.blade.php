
@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_order.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                @if(isset($order))
                    <h1 class="card-title">Order {{$order->id}}</h1>
                @else
                    <h1 class="card-title">Order</h1>
                @endif
                <button type="button" class="btn btn-secondary" id="search_orders">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                    <button type="button" class="btn btn-secondary custom-btn" id="add_oorder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                        </svg>
                    </button>
                @if(isset($order))
                    <button type="button" class="btn btn-secondary custom-btn" id="modify_order">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-secondary" id="delete_order">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                        </svg>
                    </button>
                @endif
                <hr>
                <div class="card p-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="col-form-label">Order number</label>
                            @if(isset($order))
                                <input type="text" class="form-control col custom-input" id="order_id" name="order_id" value="{{ $order->id }}" disabled>
                            @else
                                <input type="text" class="form-control col custom-input" id="order_id" name="order_id" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="email" class="col-form-label">State</label>
                            @if(isset($order))
                                <input type="email" class="form-control col custom-input" id="state" name="state" value="{{ $order->get_state() }}">
                            @else
                                <input type="email" class="form-control col custom-input" id="state" name="state" disabled>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email" class="col-form-label">Created</label>
                            @if(isset($order))
                                <input type="date" class="form-control col custom-input" id="created" name="created" value="{{ $order->get_created() }}" disabled>
                            @else
                                <input type="date" class="form-control col custom-input" id="created" name="created" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="customer_name" class="col-form-label">Customer Name</label>
                            @if(isset($order))
                                <input type="text" class="form-control col custom-input" id="customer_name" name="customer_name" value="{{ $order->customer_name }}" disabled>
                            @else
                                <input type="text" class="form-control col custom-input" id="customer_name" name="customer_name" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="total_amount" class="col-form-label">Total amount</label>
                            @if(isset($order))
                                <input type="number" class="form-control col custom-input" id="total_amount" name="total_amount" value="{{ $order->total_amount }}" disabled>
                            @else
                                <input type="number" class="form-control col custom-input" id="total_amount" name="total_amount" disabled>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($order->order_lines))
                    <div class="order_lines-container mt-3">
                        <div class="dorpdown mt-3">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Order Lines
                            </button>
                            <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                                <div class="card p-3">
                                    <h5 class="card-title">Order Lines</h5>
                                        <button type="button" class="btn btn-secondary custom-btn" id="add_order_lines">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                                            </svg>
                                        </button>
                                    <hr>
                                    <table class="order_lines_table" id="uorder_lines_table">
                                        <thead>
                                        <tr>
                                            <th>    </th>
                                            <th>Order Line</th>
                                            <th>Product Number</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Units</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($order->order_lines))
                                            @foreach($order->order_lines as $order_line)
                                                <tr>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-order-line-id="{{ $order_line->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><p class="dropdown-item modify_order_lines" id="modify_order_lines" data-order-line-id="{{ $order_line->id }}">Modify</p></li>
                                                                <li><p class="dropdown-item delete_order_lines" id="delete_order_lines" data-order-line-id="{{ $order_line->id }}">Delete</p></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="order_line_id" value="{{ $order_line->order_line }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="product_id" value="{{ $order_line->product_id }}"></td>
                                                    <td><input type="text" class="form-control" name="product_name" value="{{ $order_line->product_name }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="quantity" value="{{ $order_line->quantity }}"></td>
                                                    <td><input type="text" class="form-control" name="units" value="{{ $order_line->units }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="unit_price" value="{{ $order_line->unit_price }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="total_amount" value="{{ $order_line->total_amount }}" disabled></td>
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

    <div id="order_line_dialog" class="dialog" style="display: none;">
        <form id="order_line_form">
            @csrf
            <label>
                <input type="text" name="product_id" id="product_id" placeholder="Product number">
            </label><br>
            <label>
                <input type="text" name="quantity" id="quantity" placeholder="Quantity">
            </label><br>
            <button type="button" id="new_address">New</button>
            <button type="button" id="cancel_address">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_order_lines.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
