
@extends('layout.navigator_intra')
@section('content')
    <link href="{{ asset('css/styles_order.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                @if(isset($order))
                    <h1 class="card-title">Order - {{$order->id}}</h1>
                @else
                    <h1 class="card-title">Order</h1>
                @endif
                    <button type="button" class="btn btn-secondary custom-btn" id="add_new_order">
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
                    <div class="order_lines-container mt-3">
                        <div class="dorpdown mt-3">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Commands
                            </button>
                            <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                                <div class="card p-3">
                                    <h5 class="card-title">Commands</h5>
                                    @if(isset($order))
                                    <button href="/download_order/{{ $order->id }}" type="button" class="btn btn-primary command-btn print_order" id="print_order" data-order-id="{{ $order->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"></path>
                                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"></path>
                                        </svg>
                                        Print order
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <select id="state" class="form-select state" name="state" dis>
                                    <option selected>{{$order->state}}</option>
                                    <option>arrival</option>
                                    <option>ongoing</option>
                                    <option>processed</option>
                                </select>
                            @else
                                <select id="state" class="form-select state" name="state" disabled>
                                    <option selected>arrival</option>
                                    <option>ongoing</option>
                                    <option>processed</option>
                                </select>
                            @endif
                        </div>
                        <div class="col">
                            <label for="total_amount" class="col-form-label">Total Net Amount</label>
                            @if(isset($order))
                                <input type="number" class="form-control col custom-input" id="total_net_amount" name="total_net_amount" value="{{ $order->get_total_net_amount() }}" disabled>
                            @else
                                <input type="number" class="form-control col custom-input" id="total_net_amount" name="total_net_amount" disabled>
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
                            <label for="total_amount" class="col-form-label">Total Gross Amount</label>
                            @if(isset($order))
                                <input type="number" class="form-control col custom-input" id="total_gross_amount" name="total_gross_amount" value="{{ $order->get_total_gross_amount() }}" disabled>
                            @else
                                <input type="number" class="form-control col custom-input" id="total_gross_amount" name="total_gross_amount" disabled>
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
                                            <th>VAT Percentage</th>
                                            <th>Total Net Amount</th>
                                            <th>Total Gross Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($order->order_lines))
                                            @foreach($order->order_lines as $order_line)
                                                <tr data-order-line-id="{{ $order_line->id }}">
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-order-line-id="{{ $order_line->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><p class="dropdown-item modify_order_line" id="modify_order_lines" data-order-line-id="{{ $order_line->id }}">Modify</p></li>
                                                                <li><p class="dropdown-item delete_order_line" id="delete_order_lines" data-order-line-id="{{ $order_line->id }}">Delete</p></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="order_line_id" value="{{ $order_line->order_line }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="product_id" value="{{ $order_line->product_id }}"></td>
                                                    <td><input type="text" class="form-control" name="product_name" value="{{ $order_line->product_name }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="quantity" value="{{ $order_line->quantity }}"></td>
                                                    <td><input type="text" class="form-control" name="units" value="{{ $order_line->units }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="unit_price" value="{{ $order_line->unit_price }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="vat_percentage" value="{{ $order_line->vat_percentage }}"></td>
                                                    <td><input type="text" class="form-control" name="total_order_line_net_amount" value="{{ $order_line->total_net_amount }}" disabled></td>
                                                    <td><input type="text" class="form-control" name="total_order_line_gross_amount" value="{{ $order_line->total_gross_amount }}" disabled></td>
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
                <select id="new_product_id_select" name="new_product_id"></select>
            </label><br>
            <label>
                <input type="text" name="new_quantity" id="new_quantity" placeholder="Quantity">
            </label><br>
            <label>
                <input type="text" name="new_vat_percentage" id="new_vat_percentage" placeholder="VAT Percentage">
            </label><br>
            <button type="button" id="new_order_line">New</button>
            <button type="button" id="cancel_order_line">Cancel</button>

        </form>
    </div>

    <div id="order_dialog" class="dialog" style="display: none;">
        <form id="order_form">
            @csrf
            <label>
                <select id="customer_select" name="customer"></select>
            </label><br>
            <button type="button" id="new_order">New</button>
            <button type="button" id="cancel_order">Cancel</button>
        </form>
    </div>

    <script src="{{ asset('js/manage_order_lines.js')}}"></script>
    <script src="{{ asset('js/manage_order.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection