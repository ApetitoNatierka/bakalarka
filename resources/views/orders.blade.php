@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_orders.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Orders</h1>
                    <button type="button" class="btn btn-secondary custom-btn" id="add_new_order">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                        </svg>
                    </button>
                <button type="button" class="btn btn-secondary" id="search_orders">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($orders))
                    <div class="card p-3">
                        <table class="order_table" id="order_table">
                            <thead>
                            <tr>
                                <th>      </th>
                                <th>Order Number</th>
                                <th>State</th>
                                <th>Created</th>
                                <th>Customer name</th>
                                <th>Total amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($orders))
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-aorder-id="{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/order/{{$order->id}}" class="dropdown-item detail_order" id="detail_order" data-order-id="{{ $order->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_order" id="modify_order" data-order-id="{{ $order->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_order" id="delete_order" data-order-id="{{ $order->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="order_number" value="{{ $order->id }}" disabled></td>
                                        <td>
                                            <select id="state" class="form-select state" name="state">
                                                <option selected>{{$order->state}}</option>
                                                <option>arrival</option>
                                                <option>ongoing</option>
                                                <option>processed</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" name="created" value="{{ $order->get_created() }}" disabled></td>
                                        @if(isset($order->customer->company))
                                            <td><input type="text" class="form-control" name="customer_name" value="{{ $order->customer->company->company }}" disabled></td>
                                        @elseif($order->customer)
                                            <td><input type="text" class="form-control" name="customer_name" value="{{ $order->customer->name }}" disabled></td>
                                        @endif
                                        <td><input type="text" class="form-control" name="total_amount" value="{{ $order->get_total_amount() }}" disabled></td>
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

        <div id="order_dialog" class="dialog" style="display: none;">
            <form id="order_form">
                @csrf
                <label>
                    <input type="text" name="customer" id="customer" placeholder="Customer">
                </label><br>
                <button type="button" id="new_order">New</button>
                <button type="button" id="cancel_order">Cancel</button>
            </form>
        </div>

        <script src="{{ asset('js/manage_orders.js')}}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
