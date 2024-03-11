
@extends('layout.navigator')
@section('content')

    <link href="{{ asset('css/styles_cart.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Cart</h1>
                <hr>
                @if(isset($cart_items))
                    @foreach($cart_items as $cart_item)
                        <div class="cart_item" data-cart-item-id="{{ $cart_item->id }}">
                            <div class="card p-3">
                                @csrf
                                <table class="address_line_table" id="address_lines_table">
                                    <thead>
                                    <tr>
                                        <th>      </th>
                                        <th>Quantity</th>
                                        <th>Product Name</th>
                                        <th></th>
                                        <th>Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-cart-item-id="{{ $address->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li><p class="dropdown-item modify_address_line" id="modify_address_line" data-address-line-id="{{ $address->id }}">Modify</p></li>
                                                        <li><p class="dropdown-item delete_address_line" id="delete_address_line" data-address-line-id="{{ $address->id }}">Delete</p></li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control" name="quantity" value="{{ $cart_item->get_quantity() }}"></td>
                                            <td><p>{{ $cart_item->get_name() }}</p></td>
                                            <td></td>
                                            <td><p>{{ $cart_item->get_unit_price() * $cart_item->get_quantity()}}</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
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

        <div id="product_dialog" class="dialog" style="display: none;">
            <form id="product_form">
                @csrf
                <label>
                    <input type="text" name="product_name" id="product_name" placeholder="Name">
                </label><br>
                <label>
                    <input type="text" name="product_description" id="product_description" placeholder="Description">
                </label><br>
                <label>
                    <input type="text" name="product_price" id="product_price" placeholder="Price">
                </label><br>
                <button type="button" id="new_product">New</button>
                <button type="button" id="cancel_product">Cancel</button>
            </form>
        </div>

        <script src="{{ asset('js/manage_products.js')}}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
